export interface ApiKey {
    id: number;
    key: string;
    slug: string;
    user_id: number;
    name: string | null;
    created_at: string;
    updated_at: string;
}
