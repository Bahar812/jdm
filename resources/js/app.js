import './bootstrap';

const clamp = (value, min = 0, max = 1) => Math.min(Math.max(value, min), max);
const easeOutCubic = (value) => 1 - Math.pow(1 - value, 3);

const heroCellStart = [
    { x: -22, y: -22, scale: 0.66, rotate: -3 },
    { x: 26, y: -24, scale: 0.58, rotate: 4 },
    { x: 30, y: 24, scale: 0.56, rotate: -5 },
    { x: -24, y: 26, scale: 0.62, rotate: 5 },
    { x: 22, y: 30, scale: 0.62, rotate: -3 },
];

const initHeroScroll = () => {
    const sections = Array.from(document.querySelectorAll('[data-hero-scroll]'));
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const header = document.querySelector('header');

    if (!sections.length || reduceMotion.matches) {
        return;
    }

    let ticking = false;

    const update = () => {
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
        const scrollY = window.scrollY || window.pageYOffset;
        const navHeight = header?.offsetHeight ?? 0;
        const pinnedHeight = Math.max(viewportHeight - navHeight, 360);

        sections.forEach((section) => {
            const sticky = section.querySelector('.hero-scroll-sticky');
            const sectionTop = section.getBoundingClientRect().top + scrollY;
            const sectionHeight = section.offsetHeight;
            const pinStart = Math.max(sectionTop - navHeight, 0);
            const pinEnd = sectionTop + sectionHeight - pinnedHeight - navHeight;
            const scrollDistance = clamp(scrollY - pinStart, 0, Math.max(pinEnd - pinStart, 1));
            const galleryDistance = viewportHeight * 1.35;
            const contentDistance = viewportHeight * 0.55;
            const galleryProgress = easeOutCubic(clamp(scrollDistance / galleryDistance));
            const contentProgress = easeOutCubic(clamp(scrollDistance / contentDistance));
            const contentOpacity = 1 - contentProgress;
            const contentScale = 1 - contentProgress * 0.24;

            if (sticky) {
                sticky.style.height = `${pinnedHeight}px`;

                if (scrollY < pinStart) {
                    sticky.style.position = 'absolute';
                    sticky.style.top = '0px';
                    sticky.style.left = '0px';
                    sticky.style.width = '100%';
                } else if (scrollY > pinEnd) {
                    sticky.style.position = 'absolute';
                    sticky.style.top = `${sectionHeight - pinnedHeight}px`;
                    sticky.style.left = '0px';
                    sticky.style.width = '100%';
                } else {
                    sticky.style.position = 'fixed';
                    sticky.style.top = `${navHeight}px`;
                    sticky.style.left = '0px';
                    sticky.style.width = '100%';
                }
            }

            section.style.setProperty('--hero-gallery-progress', galleryProgress.toFixed(3));
            section.style.setProperty('--hero-content-opacity', contentOpacity.toFixed(3));
            section.style.setProperty('--hero-content-scale', contentScale.toFixed(3));

            section.querySelectorAll('.hero-cell').forEach((cell, index) => {
                const start = heroCellStart[index] ?? heroCellStart[heroCellStart.length - 1];
                const distance = 1 - galleryProgress;
                const scale = start.scale + (1 - start.scale) * galleryProgress;
                const x = start.x * distance;
                const y = start.y * distance;
                const rotate = start.rotate * distance;

                cell.style.transform = `translate3d(${x.toFixed(2)}vw, ${y.toFixed(2)}vh, 0) scale(${scale.toFixed(3)}) rotate(${rotate.toFixed(2)}deg)`;
            });
        });
    };

    const requestUpdate = () => {
        if (ticking) {
            return;
        }

        ticking = true;
        window.requestAnimationFrame(() => {
            update();
            ticking = false;
        });
    };

    update();
    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate);
};

const wrapIndex = (value, length) => ((value % length) + length) % length;

const initFocusRail = () => {
    const rails = Array.from(document.querySelectorAll('[data-focus-rail]'));

    rails.forEach((rail) => {
        const cards = Array.from(rail.querySelectorAll('[data-focus-card]'));
        const stage = rail.querySelector('[data-focus-stage]');
        const prev = rail.querySelector('[data-focus-prev]');
        const next = rail.querySelector('[data-focus-next]');
        const title = rail.querySelector('[data-focus-title]');
        const meta = rail.querySelector('[data-focus-meta]');
        const description = rail.querySelector('[data-focus-description]');
        const counter = rail.querySelector('[data-focus-counter]');

        if (!cards.length || !stage) {
            return;
        }

        let active = 0;
        let wheelLockedAt = 0;
        let pointerStartX = null;
        let didSwipe = false;

        const getOffset = (index) => {
            let offset = index - active;

            if (offset > cards.length / 2) {
                offset -= cards.length;
            }

            if (offset < -cards.length / 2) {
                offset += cards.length;
            }

            return offset;
        };

        const setActive = (nextIndex) => {
            active = wrapIndex(nextIndex, cards.length);
            const activeCard = cards[active];
            const isMobile = window.matchMedia('(max-width: 767px)').matches;
            const xStep = isMobile ? 155 : 320;
            const zStep = isMobile ? 110 : 180;

            cards.forEach((card, index) => {
                const offset = getOffset(index);
                const distance = Math.abs(offset);
                const isActive = offset === 0;
                const isVisible = distance <= 2;
                const x = offset * xStep;
                const z = -distance * zStep;
                const scale = isActive ? 1 : 0.84;
                const rotateY = offset * -18;
                const opacity = isVisible ? (isActive ? 1 : Math.max(0.12, 1 - distance * 0.42)) : 0;
                const blur = isActive ? 0 : distance * 5;
                const brightness = isActive ? 1 : 0.82;

                card.classList.toggle('is-active', isActive);
                card.setAttribute('aria-hidden', isActive ? 'false' : 'true');
                card.style.zIndex = String(30 - distance);
                card.style.pointerEvents = isVisible ? 'auto' : 'none';
                card.style.opacity = opacity.toFixed(2);
                card.style.filter = `blur(${blur}px) brightness(${brightness})`;
                card.style.transform = `translate3d(calc(-50% + ${x}px), -50%, ${z}px) scale(${scale}) rotateY(${rotateY}deg)`;
            });

            rail.style.setProperty('--focus-rail-bg', `url("${activeCard.dataset.image}")`);

            if (title) {
                title.textContent = activeCard.dataset.title || '';
            }

            if (meta) {
                meta.textContent = activeCard.dataset.meta || '';
            }

            if (description) {
                description.textContent = activeCard.dataset.description || '';
            }

            if (counter) {
                counter.textContent = `${active + 1} / ${cards.length}`;
            }
        };

        const showPrev = () => setActive(active - 1);
        const showNext = () => setActive(active + 1);

        cards.forEach((card, index) => {
            card.addEventListener('click', () => {
                if (didSwipe) {
                    didSwipe = false;
                    return;
                }

                if (index !== active) {
                    setActive(index);
                }
            });
        });

        prev?.addEventListener('click', showPrev);
        next?.addEventListener('click', showNext);

        rail.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                showPrev();
            }

            if (event.key === 'ArrowRight') {
                event.preventDefault();
                showNext();
            }
        });

        rail.addEventListener(
            'wheel',
            (event) => {
                const now = Date.now();

                if (now - wheelLockedAt < 420) {
                    return;
                }

                const delta = Math.abs(event.deltaX) > Math.abs(event.deltaY) ? event.deltaX : event.deltaY;

                if (Math.abs(delta) <= 22) {
                    return;
                }

                if (delta > 0) {
                    showNext();
                } else {
                    showPrev();
                }

                wheelLockedAt = now;
            },
            { passive: true },
        );

        stage.addEventListener('pointerdown', (event) => {
            pointerStartX = event.clientX;
        });

        stage.addEventListener('pointerup', (event) => {
            if (pointerStartX === null) {
                return;
            }

            const diff = event.clientX - pointerStartX;
            pointerStartX = null;

            if (Math.abs(diff) < 48) {
                return;
            }

            didSwipe = true;

            if (diff < 0) {
                showNext();
            } else {
                showPrev();
            }
        });

        window.addEventListener('resize', () => setActive(active));
        setActive(active);
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initHeroScroll();
        initFocusRail();
    }, { once: true });
} else {
    initHeroScroll();
    initFocusRail();
}
