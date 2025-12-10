export type Track = {
    id: number;
    title: string;
    slug: string;
    artist: string;
    album?: string | null;
    genre?: string | null;
    year?: string | null;
    duration?: string | null;
    thumbnail?: string | null;
    thumbnail_url?: string | null;
    audio_file_url?: string | null;
    play_count?: number | null;
    created_at?: string | null;
    is_visible?: boolean | null;
};

