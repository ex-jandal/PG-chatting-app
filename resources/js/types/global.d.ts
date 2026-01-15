import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import type { route as routeFn } from 'ziggy-js';
import { PageProps as AppPageProps } from './';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';


declare global {
    const route: typeof routeFn;
    interface Window {
        axios: AxiosInstance;
    }
}

declare global {
    interface Window {
        Echo: Echo;
        Pusher: typeof Pusher;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}
