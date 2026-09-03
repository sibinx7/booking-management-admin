<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { Button, Card, Heading } from '@/components';
import { useAppearance } from '@/composables/useAppearance';
import { dashboard, login } from '@/routes';

const { appearance, updateAppearance } = useAppearance();

const toggleDark = () => {
    updateAppearance(appearance.value === 'dark' ? 'light' : 'dark');
};

const navLinks = [
    { label: 'Treatments', href: '#treatments' },
    { label: 'Why Ayush', href: '#why-us' },
    { label: 'Schedule', href: '#schedule' },
];

const slides = [
    {
        image: '/images/spa-slide-1.jpg',
        badge: 'Sensual Botanical Bath • Rose Petal Therapy',
        title: 'Intimate Rejuvenation & Luxury Healing',
        subtitle: 'Warm candlelight, floating fresh rose petals, and handcrafted exotic botanical oils to awaken your senses.',
    },
    {
        image: '/images/spa-slide-2.jpg',
        badge: 'Ancient Ayurvedic Wisdom • Pure Wellness',
        title: 'Rejuvenate Your Body, Mind & Soul',
        subtitle: 'Bespoke herbal elixirs, certified master therapists, and authentic Ayurvedic therapies designed for deep tranquility.',
    },
    {
        image: '/images/spa-slide-3.jpg',
        badge: 'Private Couple Suites • Aromatic Hydrotherapy',
        title: 'Couples Sanctuary & Heated Basalt Massage',
        subtitle: 'Unwind together in our luxury mahogany suites with hot stone therapy, soothing orchid aromas, and private steam baths.',
    },
];

const currentSlide = ref(0);
let slideTimer: any = null;

const setSlide = (idx: number) => {
    currentSlide.value = idx;
    resetTimer();
};

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const prevSlide = () => {
    currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length;
};

const resetTimer = () => {
    if (slideTimer) clearInterval(slideTimer);
    slideTimer = setInterval(nextSlide, 6500);
};

onMounted(() => {
    resetTimer();
});

onUnmounted(() => {
    if (slideTimer) clearInterval(slideTimer);
});

const treatments = [
    {
        title: 'Exotic Rose & Milk Hydrotherapy',
        duration: '60 / 90 mins',
        tag: 'Sensual Botanical',
        icon: 'mdi-flower-poppy',
        color: 'primary',
        description: 'Warm private stone bath infused with fresh rose petals, pure jasmine oils, and botanical essences to soothe and nourish the skin.',
    },
    {
        title: 'Abhyanga Herbal Oil Ritual',
        duration: '60 / 90 mins',
        tag: 'Signature Ayurvedic',
        icon: 'mdi-leaf',
        color: 'primary',
        description: 'Traditional full-body warm herbal oil massage designed to improve circulation, calm the nervous system, and flush out toxins.',
    },
    {
        title: 'Shirodhara Mind & Soul Stream',
        duration: '45 mins',
        tag: 'Deep Relaxation',
        icon: 'mdi-water-well-outline',
        color: 'secondary',
        description: 'A continuous stream of warm medicated herbal oil gently poured over the forehead to relieve stress, insomnia, and anxiety.',
    },
    {
        title: 'Hot Basalt Stone Couple Suite',
        duration: '75 / 90 mins',
        tag: 'Couples Luxury',
        icon: 'mdi-hand-back-right-outline',
        color: 'accent',
        description: 'Volcanic heated basalt stones paired with warm essential oils to melt away tension and restore harmonious vitality.',
    },
];

const highlights = [
    {
        icon: 'mdi-shield-check-outline',
        title: 'Certified Ayurvedic Masters',
        desc: 'All therapists are certified practitioners with extensive clinical and holistic spa training.',
    },
    {
        icon: 'mdi-bottle-tonic-plus-outline',
        title: '100% Organic Herbal Oils',
        desc: 'Handcrafted cold-pressed oils infused with organic Ayurvedic roots and botanicals.',
    },
    {
        icon: 'mdi-spa-outline',
        title: 'Private Treatment Suites',
        desc: 'Serene, soundproofed suites with individual steam baths and aromatic relaxation lounges.',
    },
];

const stats = [
    {
        value: '15+',
        label: 'Signature Therapies',
        icon: 'mdi-spa',
        lightBg: 'rgba(255, 255, 255, 0.94)',
        darkBg: 'rgba(30, 14, 34, 0.9)',
        textColor: '#BE185D',
        darkTextColor: '#FB7185',
        borderColor: 'rgba(190, 24, 93, 0.35)',
    },
    {
        value: '12',
        label: 'Certified Therapists',
        icon: 'mdi-account-heart',
        lightBg: 'rgba(255, 255, 255, 0.94)',
        darkBg: 'rgba(30, 14, 34, 0.9)',
        textColor: '#D97706',
        darkTextColor: '#FBBF24',
        borderColor: 'rgba(217, 119, 6, 0.35)',
    },
    {
        value: '4.9 ★',
        label: 'Guest Rating',
        icon: 'mdi-star',
        lightBg: 'rgba(255, 255, 255, 0.94)',
        darkBg: 'rgba(30, 14, 34, 0.9)',
        textColor: '#E11D48',
        darkTextColor: '#FDA4AF',
        borderColor: 'rgba(225, 29, 72, 0.35)',
    },
    {
        value: '9k+',
        label: 'Sessions Done',
        icon: 'mdi-check-decagram',
        lightBg: 'rgba(255, 255, 255, 0.94)',
        darkBg: 'rgba(30, 14, 34, 0.9)',
        textColor: '#9D174D',
        darkTextColor: '#F472B6',
        borderColor: 'rgba(157, 23, 77, 0.35)',
    },
];
</script>

<template>
    <Head title="Ayush Spa - Exotic Ayurvedic & Luxury Wellness Sanctuary" />

    <v-app class="spa-page-wrapper">
        <!-- Catchy Luxury Floating Island Header -->
        <header class="spa-floating-header-wrap">
            <nav class="spa-floating-island d-flex align-center justify-space-between px-5 py-2">
                <!-- Brand Logo & Badge -->
                <div class="d-flex align-center">
                    <div class="spa-logo-glow-wrap mr-3">
                        <v-avatar color="primary" size="38" class="elevation-2">
                            <v-icon icon="mdi-spa" color="white" size="22" />
                        </v-avatar>
                    </div>
                    <div class="brand-text-block">
                        <span class="text-subtitle-1 font-weight-bold d-block leading-none text-white spa-brand-title">
                            Ayush
                        </span>
                        <span class="text-caption text-grey-lighten-2 leading-none" style="font-size: 0.72rem;">
                            Luxury Spa Sanctuary
                        </span>
                    </div>
                </div>

                <!-- Center Nav Links -->
                <div class="d-none d-md-flex align-center ga-1 nav-pill-group">
                    <a
                        v-for="(link, idx) in navLinks"
                        :key="idx"
                        :href="link.href"
                        class="nav-pill-link"
                    >
                        {{ link.label }}
                    </a>
                </div>

                <!-- Right Actions & CTA -->
                <div class="d-flex align-center ga-2">
                    <v-btn
                        icon
                        size="small"
                        variant="text"
                        @click="toggleDark"
                        class="text-white nav-theme-btn"
                        :title="appearance === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                    >
                        <v-icon :icon="appearance === 'dark' ? 'mdi-white-balance-sunny' : 'mdi-weather-night'" size="18" />
                    </v-btn>

                    <Button
                        v-if="$page.props.auth?.user"
                        :href="dashboard.url()"
                        variant="flat"
                        size="small"
                        color="primary"
                        append-icon="mdi-arrow-right"
                        class="font-weight-bold px-4 spa-cta-pill elevation-2"
                    >
                        Dashboard
                    </Button>
                    <Button
                        v-else
                        :href="login.url()"
                        variant="flat"
                        size="small"
                        color="primary"
                        append-icon="mdi-arrow-right"
                        class="font-weight-bold px-4 spa-cta-pill elevation-2"
                    >
                        Admin Login
                    </Button>
                </div>
            </nav>
        </header>

        <v-main>
            <!-- Hero Section: Smooth Crossfade Exotic Background Slider -->
            <section class="hero-section py-20 px-4 position-relative overflow-hidden">
                <!-- Background Crossfade Slider Images -->
                <div
                    v-for="(slide, idx) in slides"
                    :key="idx"
                    class="spa-hero-slide"
                    :class="{ active: currentSlide === idx }"
                    :style="{ backgroundImage: `url(${slide.image})` }"
                />

                <!-- Vignette Overlay -->
                <div class="hero-vignette-overlay" />

                <!-- Hero Content -->
                <v-container class="position-relative" style="z-index: 3;">
                    <v-row align="center" justify="center" class="text-center">
                        <v-col cols="12" md="10" lg="9">
                            <!-- Dynamic Slide Badge -->
                            <v-chip color="primary" variant="flat" class="mb-5 font-weight-bold px-5 text-white spa-badge elevation-3" size="large">
                                <v-icon icon="mdi-flower-poppy" class="mr-2" />
                                {{ slides[currentSlide].badge }}
                            </v-chip>

                            <!-- Dynamic Slide Headline with Sensual Rose Gold Gradient -->
                            <h1 class="text-h3 text-sm-h2 font-weight-bold mb-4 tracking-tight leading-tight hero-title text-white">
                                {{ slides[currentSlide].title }} at <span class="brand-hero-gold">Ayush Spa</span>
                            </h1>

                            <p class="text-body-1 text-grey-lighten-2 mb-8 max-w-2xl mx-auto hero-subtitle">
                                {{ slides[currentSlide].subtitle }}
                            </p>

                            <div class="d-flex flex-wrap justify-center ga-4 mb-10">
                                <Button
                                    v-if="$page.props.auth?.user"
                                    :href="dashboard.url()"
                                    size="x-large"
                                    append-icon="mdi-arrow-right"
                                    color="primary"
                                    class="elevation-4 font-weight-bold text-white px-8 hero-main-btn"
                                >
                                    Open Management Dashboard
                                </Button>
                                <Button
                                    v-else
                                    :href="login.url()"
                                    size="x-large"
                                    append-icon="mdi-arrow-right"
                                    color="primary"
                                    class="elevation-4 font-weight-bold text-white px-8 hero-main-btn"
                                >
                                    Admin Management Portal
                                </Button>
                            </div>

                            <!-- Equal Height Stat Badges -->
                            <v-row class="mt-4 justify-center align-stretch">
                                <v-col v-for="(stat, idx) in stats" :key="idx" cols="6" sm="3" class="d-flex">
                                    <div
                                        class="pa-3 w-100 d-flex flex-column justify-center align-center stat-card"
                                        :style="{
                                            backgroundColor: appearance === 'dark' ? stat.darkBg : stat.lightBg,
                                            borderColor: stat.borderColor,
                                        }"
                                    >
                                        <v-icon
                                            :icon="stat.icon"
                                            size="22"
                                            class="mb-1"
                                            :style="{ color: appearance === 'dark' ? stat.darkTextColor : stat.textColor }"
                                        />
                                        <div
                                            class="text-subtitle-1 font-weight-bold leading-tight"
                                            :style="{ color: appearance === 'dark' ? stat.darkTextColor : stat.textColor }"
                                        >
                                            {{ stat.value }}
                                        </div>
                                        <div class="text-caption font-weight-medium text-medium-emphasis text-center leading-tight mt-1">
                                            {{ stat.label }}
                                        </div>
                                    </div>
                                </v-col>
                            </v-row>

                            <!-- Slider Indicators & Controls -->
                            <div class="d-flex align-center justify-center ga-2 mt-8">
                                <v-btn
                                    icon
                                    size="x-small"
                                    variant="tonal"
                                    color="white"
                                    @click="prevSlide"
                                    class="slider-nav-btn mr-2"
                                    title="Previous Slide"
                                >
                                    <v-icon icon="mdi-chevron-left" size="18" />
                                </v-btn>

                                <button
                                    v-for="(_, idx) in slides"
                                    :key="idx"
                                    class="slider-dot"
                                    :class="{ active: currentSlide === idx }"
                                    @click="setSlide(idx)"
                                    :aria-label="`Go to slide ${idx + 1}`"
                                />

                                <v-btn
                                    icon
                                    size="x-small"
                                    variant="tonal"
                                    color="white"
                                    @click="nextSlide"
                                    class="slider-nav-btn ml-2"
                                    title="Next Slide"
                                >
                                    <v-icon icon="mdi-chevron-right" size="18" />
                                </v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </section>

            <!-- Signature Treatments Section -->
            <section id="treatments" class="py-16 px-4 treatments-section position-relative">
                <v-container>
                    <div class="text-center mb-12">
                        <v-chip color="primary" variant="tonal" size="small" class="mb-2 font-weight-bold">
                            TREATMENT MENU
                        </v-chip>
                        <h2 class="text-h4 font-weight-bold mb-2">Signature Holistic & Exotic Therapies</h2>
                        <p class="text-body-2 text-medium-emphasis">Tailored to your body type, dosha balance, and sensual wellness goals</p>
                    </div>

                    <v-row>
                        <v-col v-for="(treatment, idx) in treatments" :key="idx" cols="12" md="6">
                            <Card class="pa-6 h-100 d-flex flex-column justify-space-between hover-elevation glass-treatment-card" elevation="2">
                                <div>
                                    <div class="d-flex align-center justify-space-between mb-3">
                                        <v-avatar :color="treatment.color" variant="tonal" size="48">
                                            <v-icon :icon="treatment.icon" size="26" />
                                        </v-avatar>
                                        <v-chip size="small" variant="tonal" color="primary" class="font-weight-medium">
                                            {{ treatment.duration }}
                                        </v-chip>
                                    </div>
                                    <div class="text-caption font-weight-bold text-uppercase text-secondary mb-1">
                                        {{ treatment.tag }}
                                    </div>
                                    <h3 class="text-h6 font-weight-bold mb-2">{{ treatment.title }}</h3>
                                    <p class="text-body-2 text-medium-emphasis mb-0 leading-relaxed">{{ treatment.description }}</p>
                                </div>
                            </Card>
                        </v-col>
                    </v-row>
                </v-container>
            </section>

            <!-- Why Choose Ayush Standard -->
            <section id="why-us" class="py-16 px-4">
                <v-container>
                    <div class="text-center mb-12">
                        <v-chip color="primary" variant="tonal" size="small" class="mb-2 font-weight-bold">
                            THE AYUSH STANDARD
                        </v-chip>
                        <h2 class="text-h4 font-weight-bold mb-2">Why Guests Choose Ayush Spa</h2>
                        <p class="text-body-2 text-medium-emphasis">Dedicated to traditional integrity, organic purity, and therapeutic luxury</p>
                    </div>

                    <v-row>
                        <v-col v-for="(item, idx) in highlights" :key="idx" cols="12" md="4">
                            <Card class="pa-6 text-center h-100 glass-treatment-card" elevation="1">
                                <v-avatar color="primary" variant="tonal" size="56" class="mb-4">
                                    <v-icon :icon="item.icon" size="30" />
                                </v-avatar>
                                <h3 class="text-subtitle-1 font-weight-bold mb-2">{{ item.title }}</h3>
                                <p class="text-body-2 text-medium-emphasis mb-0">{{ item.desc }}</p>
                            </Card>
                        </v-col>
                    </v-row>
                </v-container>
            </section>

            <!-- Operating Hours & Staff Gateway -->
            <section id="schedule" class="py-12 px-4 border-t schedule-banner">
                <v-container>
                    <v-row align="center" justify="space-between">
                        <v-col cols="12" md="7">
                            <div class="d-flex align-center mb-2">
                                <v-avatar color="primary" size="38" class="mr-3 elevation-1">
                                    <v-icon icon="mdi-clock-outline" color="white" size="22" />
                                </v-avatar>
                                <h3 class="text-h6 font-weight-bold mb-0">Spa Working Hours & Schedule</h3>
                            </div>
                            <p class="text-body-2 text-medium-emphasis mb-0">
                                Open Daily: <strong>08:00 AM – 09:00 PM</strong> • Appointments & Therapist Scheduling Managed via Admin
                            </p>
                        </v-col>
                        <v-col cols="12" md="5" class="text-md-right mt-4 mt-md-0">
                            <Button
                                :href="login.url()"
                                variant="outlined"
                                color="primary"
                                prepend-icon="mdi-lock-outline"
                                class="font-weight-bold"
                            >
                                Staff / Admin Access
                            </Button>
                        </v-col>
                    </v-row>
                </v-container>
            </section>
        </v-main>

        <!-- Footer -->
        <v-footer class="py-6 border-t text-center d-flex flex-column bg-transparent">
            <div class="d-flex align-center justify-center mb-2">
                <v-icon icon="mdi-spa" color="primary" class="mr-2" />
                <span class="font-weight-bold spa-brand-title">Ayush Spa & Wellness Center</span>
            </div>
            <div class="text-caption text-medium-emphasis">
                © {{ new Date().getFullYear() }} Ayush Spa Management. All rights reserved.
            </div>
        </v-footer>
    </v-app>
</template>

<style scoped>
.spa-page-wrapper {
    background: linear-gradient(135deg, #fff1f2 0%, #faf5ff 40%, #fffbeb 100%);
}

.dark .spa-page-wrapper {
    background: linear-gradient(135deg, #110713 0%, #1a0b1d 50%, #150918 100%);
}

/* Floating Island Header Styling */
.spa-floating-header-wrap {
    position: fixed;
    top: 18px;
    left: 0;
    right: 0;
    z-index: 100;
    display: flex;
    justify-content: center;
    padding: 0 1rem;
    pointer-events: none;
}

.spa-floating-island {
    pointer-events: auto;
    width: 100%;
    max-width: 1040px;
    backdrop-filter: blur(20px);
    background: rgba(26, 11, 29, 0.78);
    border: 1px solid rgba(255, 255, 255, 0.16);
    box-shadow: 0 10px 32px rgba(0, 0, 0, 0.4), 0 0 1px rgba(255, 255, 255, 0.2) inset;
    border-radius: 9999px;
    transition: all 0.3s ease;
}

.spa-logo-glow-wrap {
    filter: drop-shadow(0 0 10px rgba(190, 24, 93, 0.8));
}

.spa-brand-title {
    font-family: 'Instrument Sans', sans-serif;
    letter-spacing: -0.02em;
}

.nav-pill-group {
    background: rgba(255, 255, 255, 0.08);
    padding: 3px 6px;
    border-radius: 9999px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-pill-link {
    color: rgba(255, 255, 255, 0.88);
    font-size: 0.85rem;
    font-weight: 500;
    padding: 6px 14px;
    border-radius: 9999px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.nav-pill-link:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.16);
    text-decoration: none;
}

.nav-theme-btn {
    border-radius: 50%;
    transition: background 0.2s ease;
}

.nav-theme-btn:hover {
    background: rgba(255, 255, 255, 0.14);
}

.spa-cta-pill {
    border-radius: 9999px !important;
    background: linear-gradient(135deg, #be185d 0%, #e11d48 100%) !important;
    box-shadow: 0 4px 14px rgba(190, 24, 93, 0.45) !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.spa-cta-pill:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(190, 24, 93, 0.65) !important;
}

.hero-main-btn {
    border-radius: 9999px !important;
    background: linear-gradient(135deg, #be185d 0%, #e11d48 100%) !important;
    box-shadow: 0 6px 20px rgba(190, 24, 93, 0.5) !important;
}

.hero-section {
    min-height: 94vh;
    display: flex;
    align-items: center;
    position: relative;
    padding-top: 120px !important;
    background-color: #120712;
}

.spa-hero-slide {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center 30%;
    opacity: 0;
    transition: opacity 1.4s ease-in-out, transform 8s ease-out;
    transform: scale(1.04);
    z-index: 1;
}

.spa-hero-slide.active {
    opacity: 1;
    transform: scale(1);
}

.hero-vignette-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        180deg,
        rgba(18, 7, 18, 0.62) 0%,
        rgba(18, 7, 18, 0.8) 55%,
        rgba(18, 7, 18, 0.96) 100%
    );
    z-index: 2;
}

.hero-title {
    text-shadow: 0 4px 24px rgba(0, 0, 0, 0.8);
}

.hero-subtitle {
    text-shadow: 0 2px 14px rgba(0, 0, 0, 0.9);
}

.brand-hero-gold {
    background: linear-gradient(120deg, #f43f5e 0%, #fb7185 40%, #fbbf24 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 2px 24px rgba(244, 63, 94, 0.5);
}

.stat-card {
    border-radius: 14px;
    border-width: 1px;
    border-style: solid;
    min-height: 94px;
    backdrop-filter: blur(12px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 28px rgba(190, 24, 93, 0.4);
}

.slider-dot {
    width: 28px;
    height: 6px;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.35);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.slider-dot.active {
    width: 44px;
    background: #be185d;
    box-shadow: 0 0 10px rgba(190, 24, 93, 0.8);
}

.slider-nav-btn {
    backdrop-filter: blur(8px);
    background: rgba(255, 255, 255, 0.15) !important;
}

.glass-treatment-card {
    backdrop-filter: blur(12px);
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid rgba(190, 24, 93, 0.15);
    border-radius: 18px;
}

.dark .glass-treatment-card {
    background: rgba(28, 13, 31, 0.88);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.treatments-section {
    background: rgba(255, 255, 255, 0.6);
}

.dark .treatments-section {
    background: rgba(18, 7, 18, 0.7);
}

.schedule-banner {
    background: rgba(255, 255, 255, 0.75);
}

.dark .schedule-banner {
    background: rgba(18, 7, 18, 0.85);
}

.hover-elevation {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-elevation:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(190, 24, 93, 0.22) !important;
}

.max-w-2xl {
    max-width: 42rem;
}
</style>
