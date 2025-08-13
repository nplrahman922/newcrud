export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    role: string; // <-- TAMBAHKAN BARIS INI
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

export type BreadcrumbItem = {
    title: string;
    href?: string;
};
