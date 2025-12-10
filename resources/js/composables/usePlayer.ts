import { ref, computed, watch } from 'vue';
import type { Track } from '@/types/track';

// Global audio element
let audioElement: HTMLAudioElement | null = null;

// Global state (singleton pattern)
const trackList = ref<Track[]>([]);
const currentIndex = ref<number>(-1);
const isPlaying = ref(false);
const currentTime = ref(0);
const duration = ref(0);
const volume = ref(75);
const isLoading = ref(false);

const currentTrack = computed<Track | null>(() => {
    if (currentIndex.value < 0 || currentIndex.value >= trackList.value.length) {
        return null;
    }
    return trackList.value[currentIndex.value];
});

const hasNext = computed(() => currentIndex.value < trackList.value.length - 1);
const hasPrevious = computed(() => currentIndex.value > 0);

// Progress percentage (0-100)
const progress = computed(() => {
    if (duration.value === 0) return 0;
    return (currentTime.value / duration.value) * 100;
});

// Format time in mm:ss
const formatTime = (seconds: number): string => {
    if (isNaN(seconds) || seconds < 0) return '0:00';
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs.toString().padStart(2, '0')}`;
};

const formattedCurrentTime = computed(() => formatTime(currentTime.value));
const formattedDuration = computed(() => formatTime(duration.value));

function initAudio() {
    if (audioElement) return;
    
    audioElement = new Audio();
    audioElement.volume = volume.value / 100;
    
    audioElement.addEventListener('timeupdate', () => {
        currentTime.value = audioElement?.currentTime ?? 0;
    });
    
    audioElement.addEventListener('durationchange', () => {
        duration.value = audioElement?.duration ?? 0;
    });
    
    audioElement.addEventListener('ended', () => {
        next();
    });
    
    audioElement.addEventListener('play', () => {
        isPlaying.value = true;
        isLoading.value = false;
    });
    
    audioElement.addEventListener('pause', () => {
        isPlaying.value = false;
    });
    
    audioElement.addEventListener('loadstart', () => {
        isLoading.value = true;
    });
    
    audioElement.addEventListener('canplay', () => {
        isLoading.value = false;
    });
    
    audioElement.addEventListener('error', () => {
        isLoading.value = false;
        isPlaying.value = false;
        console.error('Error loading audio');
    });
}

function setPlaylist(tracks: Track[], startIndex: number = 0) {
    initAudio();
    trackList.value = tracks;
    currentIndex.value = startIndex;
    loadAndPlay();
}

function playTrack(track: Track) {
    const index = trackList.value.findIndex(t => t.id === track.id);
    if (index !== -1) {
        currentIndex.value = index;
        loadAndPlay();
    } else {
        // Track not in current playlist, add it and play
        trackList.value = [track];
        currentIndex.value = 0;
        loadAndPlay();
    }
}

function loadAndPlay() {
    if (!audioElement || !currentTrack.value) return;
    
    const audioUrl = currentTrack.value.audio_file_url;
    if (!audioUrl) {
        console.warn('No audio URL for track:', currentTrack.value.title);
        return;
    }
    
    audioElement.src = audioUrl;
    audioElement.load();
    audioElement.play().catch(err => {
        console.error('Error playing audio:', err);
    });
}

function play() {
    if (!audioElement) {
        initAudio();
    }
    
    if (!currentTrack.value || !currentTrack.value.audio_file_url) {
        return;
    }
    
    // If no source set yet, load current track
    if (!audioElement?.src || audioElement.src !== currentTrack.value.audio_file_url) {
        loadAndPlay();
    } else {
        audioElement?.play();
    }
}

function pause() {
    audioElement?.pause();
}

function togglePlay() {
    if (isPlaying.value) {
        pause();
    } else {
        play();
    }
}

function next() {
    if (hasNext.value) {
        currentIndex.value++;
        loadAndPlay();
    } else if (trackList.value.length > 0) {
        // Loop back to first track
        currentIndex.value = 0;
        loadAndPlay();
    }
}

function previous() {
    // If more than 3 seconds into song, restart it
    if (currentTime.value > 3) {
        seek(0);
        return;
    }
    
    if (hasPrevious.value) {
        currentIndex.value--;
        loadAndPlay();
    } else if (trackList.value.length > 0) {
        // Loop to last track
        currentIndex.value = trackList.value.length - 1;
        loadAndPlay();
    }
}

function seek(time: number) {
    if (audioElement) {
        audioElement.currentTime = time;
    }
}

function seekToPercent(percent: number) {
    if (audioElement && duration.value > 0) {
        audioElement.currentTime = (percent / 100) * duration.value;
    }
}

function setVolume(newVolume: number) {
    volume.value = Math.max(0, Math.min(100, newVolume));
    if (audioElement) {
        audioElement.volume = volume.value / 100;
    }
}

// Watch for volume changes
watch(volume, (newVolume) => {
    if (audioElement) {
        audioElement.volume = newVolume / 100;
    }
});

export function usePlayer() {
    // Initialize audio on first use
    initAudio();
    
    return {
        // State
        trackList,
        currentTrack,
        currentIndex,
        isPlaying,
        isLoading,
        currentTime,
        duration,
        volume,
        progress,
        formattedCurrentTime,
        formattedDuration,
        hasNext,
        hasPrevious,
        
        // Methods
        setPlaylist,
        playTrack,
        play,
        pause,
        togglePlay,
        next,
        previous,
        seek,
        seekToPercent,
        setVolume,
    };
}
