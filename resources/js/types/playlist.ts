export type Playlist = {
    id: number;
    name: string;
    slug: string;
    description?: string | null;
    image?: string | null;
    user_id: number;
    created_at?: string | null;
    tracks_count?: number;
};
