import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const homePage = document.body.dataset.page === 'home';

    const header = document.querySelector('[data-home-header]');
    const mobileMenuTrigger = document.querySelector('[data-mobile-menu-trigger]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    const mobileServicesTrigger = document.querySelector('[data-mobile-services-trigger]');
    const mobileServicesPanel = document.querySelector('[data-mobile-services-panel]');
    const mobileServicesIcon = document.querySelector('[data-mobile-services-icon]');
    const desktopMenuLinks = document.querySelectorAll('[data-nav-link]');
    const desktopMenuTriggers = document.querySelectorAll('[data-nav-trigger]');
    const languageButton = document.querySelector('[data-language-trigger]');

    const closeAllDropdowns = () => {
        document.querySelectorAll('[data-dropdown]').forEach((dropdown) => {
            dropdown.querySelector('[data-dropdown-panel]')?.classList.add('hidden');
            dropdown.querySelector('[data-dropdown-icon]')?.classList.remove('rotate-180');
        });
    };

    const updateHeaderState = () => {
        if (! header) {
            return;
        }

        if (! homePage) {
            header.classList.remove('bg-transparent', 'py-5');
            header.classList.add('bg-card/95', 'py-2.5', 'shadow-lg', 'backdrop-blur-xl');

            desktopMenuLinks.forEach((link) => {
                link.classList.remove('text-primary-foreground/90');
                link.classList.add('text-foreground');
            });

            desktopMenuTriggers.forEach((trigger) => {
                trigger.classList.remove('text-primary-foreground/90');
                trigger.classList.add('text-foreground');
            });

            header.querySelectorAll('a[href^="tel:"], [data-mobile-menu-trigger]').forEach((element) => {
                element.classList.remove('text-primary-foreground');
                element.classList.add('text-foreground');
            });

            if (languageButton) {
                languageButton.classList.remove('text-primary-foreground/80', 'border-primary-foreground/20', 'hover:border-primary-foreground/40');
                languageButton.classList.add('text-foreground', 'border-border/60', 'hover:border-primary/40');
            }

            return;
        }

        const scrolled = window.scrollY > 50;

        header.classList.toggle('bg-transparent', ! scrolled);
        header.classList.toggle('py-5', ! scrolled);
        header.classList.toggle('bg-card/95', scrolled);
        header.classList.toggle('backdrop-blur-xl', scrolled);
        header.classList.toggle('shadow-lg', scrolled);
        header.classList.toggle('py-2.5', scrolled);

        desktopMenuLinks.forEach((link) => {
            link.classList.toggle('text-primary-foreground/90', ! scrolled);
            link.classList.toggle('text-foreground', scrolled);
        });

        desktopMenuTriggers.forEach((trigger) => {
            trigger.classList.toggle('text-primary-foreground/90', ! scrolled);
            trigger.classList.toggle('text-foreground', scrolled);
        });

        header.querySelectorAll('a[href^="tel:"], [data-mobile-menu-trigger]').forEach((element) => {
            element.classList.toggle('text-primary-foreground', ! scrolled);
            element.classList.toggle('text-foreground', scrolled);
        });

        if (languageButton) {
            languageButton.classList.toggle('text-primary-foreground/80', ! scrolled);
            languageButton.classList.toggle('border-primary-foreground/20', ! scrolled);
            languageButton.classList.toggle('hover:border-primary-foreground/40', ! scrolled);
            languageButton.classList.toggle('text-foreground', scrolled);
            languageButton.classList.toggle('border-border/60', scrolled);
            languageButton.classList.toggle('hover:border-primary/40', scrolled);
        }
    };

    updateHeaderState();
    window.addEventListener('scroll', updateHeaderState, { passive: true });

    document.querySelectorAll('[data-dropdown-trigger]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.stopPropagation();

            const dropdown = trigger.closest('[data-dropdown]');
            const panel = dropdown?.querySelector('[data-dropdown-panel]');
            const icon = dropdown?.querySelector('[data-dropdown-icon]');
            const isHidden = panel?.classList.contains('hidden');

            closeAllDropdowns();

            if (isHidden) {
                panel?.classList.remove('hidden');
                icon?.classList.add('rotate-180');
            }
        });
    });

    document.addEventListener('click', () => {
        closeAllDropdowns();
    });

    mobileMenuTrigger?.addEventListener('click', () => {
        mobileMenu?.classList.toggle('hidden');
    });

    mobileServicesTrigger?.addEventListener('click', () => {
        mobileServicesPanel?.classList.toggle('hidden');
        mobileServicesPanel?.classList.toggle('flex');
        mobileServicesIcon?.classList.toggle('rotate-180');
    });

    document.querySelectorAll('[data-mobile-menu] a').forEach((link) => {
        link.addEventListener('click', () => {
            mobileMenu?.classList.add('hidden');
            mobileServicesPanel?.classList.add('hidden');
            mobileServicesPanel?.classList.remove('flex');
            mobileServicesIcon?.classList.remove('rotate-180');
        });
    });

    const revealElements = document.querySelectorAll('[data-reveal]');
    const heroSection = document.querySelector('#home');
    const heroBackgrounds = heroSection?.querySelectorAll('[data-hero-bg]') ?? [];
    const heroContents = heroSection?.querySelectorAll('[data-hero-content]') ?? [];
    const heroIndicators = heroSection?.querySelectorAll('[data-hero-indicator]') ?? [];
    const testimonialsSlider = document.querySelector('[data-testimonials-slider]');
    const testimonialSlides = testimonialsSlider?.querySelectorAll('[data-testimonial-slide]') ?? [];
    const testimonialIndicators = testimonialsSlider?.querySelectorAll('[data-testimonial-indicator]') ?? [];
    const testimonialPrev = testimonialsSlider?.querySelector('[data-testimonial-prev]');
    const testimonialNext = testimonialsSlider?.querySelector('[data-testimonial-next]');
    const partnersSlider = document.querySelector('[data-partners-slider]');
    const partnersViewport = partnersSlider?.querySelector('[data-partners-viewport]');
    const partnersTrack = partnersSlider?.querySelector('[data-partners-track]');
    const partnerSlides = partnersTrack ? Array.from(partnersTrack.querySelectorAll('[data-partner-slide]')) : [];
    const partnersPrev = partnersSlider?.querySelector('[data-partners-prev]');
    const partnersNext = partnersSlider?.querySelector('[data-partners-next]');
    const isRtl = document.documentElement.dir === 'rtl';
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const packageFilter = document.querySelector('#filter');
    const packageFilterButtons = packageFilter?.querySelectorAll('[data-package-filter]') ?? [];
    const packageCards = document.querySelectorAll('[data-package-card]');
    const packageFilterEmpty = document.querySelector('[data-package-filter-empty]');
    const packageGallery = document.querySelector('[data-package-gallery]');
    const packageGalleryFeaturedImage = packageGallery?.querySelector('[data-package-gallery-featured]');
    const packageGalleryCaptionWrapper = packageGallery?.querySelector('[data-package-gallery-caption-wrapper]');
    const packageGalleryCaption = packageGallery?.querySelector('[data-package-gallery-caption]');
    const packageGalleryThumbnails = packageGallery?.querySelectorAll('[data-package-gallery-thumbnail]') ?? [];

    if (packageGallery && packageGalleryFeaturedImage && packageGalleryThumbnails.length > 0) {
        const activeThumbnailClasses = ['ring-2', 'ring-primary', 'ring-offset-2', 'ring-offset-background'];
        const inactiveThumbnailClasses = ['ring-0', 'ring-transparent', 'ring-offset-0'];

        const setActivePackageGalleryThumbnail = (activeThumbnail) => {
            packageGalleryThumbnails.forEach((thumbnail) => {
                const isActive = thumbnail === activeThumbnail;

                thumbnail.setAttribute('aria-pressed', String(isActive));
                activeThumbnailClasses.forEach((className) => {
                    thumbnail.classList.toggle(className, isActive);
                });
                inactiveThumbnailClasses.forEach((className) => {
                    thumbnail.classList.toggle(className, ! isActive);
                });
            });
        };

        packageGalleryThumbnails.forEach((thumbnail) => {
            thumbnail.addEventListener('click', () => {
                const imageSrc = thumbnail.dataset.packageGallerySrc;
                const imageAlt = thumbnail.dataset.packageGalleryAlt;
                const imageCaption = thumbnail.dataset.packageGalleryCaption;

                if (imageSrc) {
                    packageGalleryFeaturedImage.src = imageSrc;
                }

                if (imageAlt) {
                    packageGalleryFeaturedImage.alt = imageAlt;
                }

                if (packageGalleryCaption && packageGalleryCaptionWrapper) {
                    packageGalleryCaption.textContent = imageCaption ?? '';
                    packageGalleryCaptionWrapper.classList.toggle('hidden', ! imageCaption);
                }

                setActivePackageGalleryThumbnail(thumbnail);
            });
        });
    }

    document.querySelectorAll('[data-package-accordion]').forEach((accordion) => {
        const accordionItems = accordion.querySelectorAll('[data-package-accordion-item]');

        accordionItems.forEach((item) => {
            const trigger = item.querySelector('[data-package-accordion-trigger]');
            const panel = item.querySelector('[data-package-accordion-panel]');
            const chevron = trigger?.querySelector('svg');

            trigger?.addEventListener('click', () => {
                const isOpen = item.dataset.state === 'open';

                item.dataset.state = isOpen ? 'closed' : 'open';
                trigger.dataset.state = isOpen ? 'closed' : 'open';
                trigger.setAttribute('aria-expanded', String(! isOpen));
                panel.dataset.state = isOpen ? 'closed' : 'open';
                panel.classList.toggle('hidden', isOpen);
                chevron?.classList.toggle('rotate-180', ! isOpen);
            });
        });
    });

    if (packageFilterButtons.length > 0 && packageCards.length > 0) {
        const activeClasses = ['bg-primary', 'text-primary-foreground', 'border-primary', 'shadow-[0_4px_16px_-4px_hsl(var(--primary)/0.5)]'];
        const inactiveClasses = ['bg-card', 'text-foreground', 'border-border', 'hover:border-primary', 'hover:text-primary'];

        const setActiveFilterButton = (activeButton) => {
            packageFilterButtons.forEach((button) => {
                const isActive = button === activeButton;

                button.setAttribute('aria-pressed', String(isActive));
                button.classList.toggle(activeClasses[0], isActive);
                button.classList.toggle(activeClasses[1], isActive);
                button.classList.toggle(activeClasses[2], isActive);
                button.classList.toggle(activeClasses[3], isActive);
                inactiveClasses.forEach((className) => {
                    button.classList.toggle(className, ! isActive);
                });
            });
        };

        const filterPackages = (destination) => {
            let visiblePackages = 0;

            packageCards.forEach((card) => {
                const shouldShow = destination === 'all' || card.dataset.destination === destination;

                card.classList.toggle('hidden', ! shouldShow);

                if (shouldShow) {
                    visiblePackages += 1;
                }
            });

            packageFilterEmpty?.classList.toggle('hidden', visiblePackages > 0);
        };

        packageFilterButtons.forEach((button) => {
            button.addEventListener('click', () => {
                setActiveFilterButton(button);
                filterPackages(button.dataset.packageFilter ?? 'all');
            });
        });
    }

    if (heroBackgrounds.length > 1 && heroContents.length === heroBackgrounds.length) {
        let activeHeroSlide = 0;
        let heroIntervalId;

        const renderHeroSlide = (nextIndex) => {
            activeHeroSlide = nextIndex;

            heroBackgrounds.forEach((background, index) => {
                background.style.opacity = index === activeHeroSlide ? '1' : '0';
            });

            heroContents.forEach((content, index) => {
                const isActive = index === activeHeroSlide;

                content.classList.toggle('opacity-100', isActive);
                content.classList.toggle('translate-y-0', isActive);
                content.classList.toggle('relative', isActive);
                content.classList.toggle('pointer-events-none', ! isActive);
                content.classList.toggle('opacity-0', ! isActive);
                content.classList.toggle('translate-y-4', ! isActive);
                content.classList.toggle('absolute', ! isActive);
                content.classList.toggle('inset-x-0', ! isActive);
            });

            heroIndicators.forEach((indicator, index) => {
                const isActive = index === activeHeroSlide;

                indicator.classList.toggle('w-10', isActive);
                indicator.classList.toggle('bg-primary', isActive);
                indicator.classList.toggle('w-5', ! isActive);
                indicator.classList.toggle('bg-primary-foreground/40', ! isActive);
            });
        };

        const startHeroAutoplay = () => {
            clearInterval(heroIntervalId);
            heroIntervalId = window.setInterval(() => {
                const nextIndex = (activeHeroSlide + 1) % heroBackgrounds.length;
                renderHeroSlide(nextIndex);
            }, 6500);
        };

        heroIndicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                renderHeroSlide(index);
                startHeroAutoplay();
            });
        });

        renderHeroSlide(0);
        startHeroAutoplay();

        heroSection?.addEventListener('mouseenter', () => {
            clearInterval(heroIntervalId);
        });

        heroSection?.addEventListener('mouseleave', () => {
            startHeroAutoplay();
        });
    }

    if (testimonialSlides.length > 0) {
        let activeTestimonialSlide = 0;
        let testimonialIntervalId;

        const renderTestimonialSlide = (nextIndex) => {
            activeTestimonialSlide = nextIndex;

            testimonialSlides.forEach((slide, index) => {
                const isActive = index === activeTestimonialSlide;

                slide.classList.toggle('relative', isActive);
                slide.classList.toggle('absolute', ! isActive);
                slide.classList.toggle('inset-0', ! isActive);
                slide.classList.toggle('opacity-100', isActive);
                slide.classList.toggle('opacity-0', ! isActive);
                slide.classList.toggle('translate-x-0', isActive);
                slide.classList.toggle('translate-x-6', ! isActive && ! isRtl);
                slide.classList.toggle('-translate-x-6', ! isActive && isRtl);
                slide.classList.toggle('pointer-events-none', ! isActive);
            });

            testimonialIndicators.forEach((indicator, index) => {
                const isActive = index === activeTestimonialSlide;

                indicator.classList.toggle('w-9', isActive);
                indicator.classList.toggle('bg-primary', isActive);
                indicator.classList.toggle('w-2.5', ! isActive);
                indicator.classList.toggle('bg-border', ! isActive);
            });
        };

        const startTestimonialAutoplay = () => {
            clearInterval(testimonialIntervalId);

            if (testimonialSlides.length < 2) {
                return;
            }

            testimonialIntervalId = window.setInterval(() => {
                renderTestimonialSlide((activeTestimonialSlide + 1) % testimonialSlides.length);
            }, 5500);
        };

        testimonialPrev?.addEventListener('click', () => {
            renderTestimonialSlide((activeTestimonialSlide - 1 + testimonialSlides.length) % testimonialSlides.length);
            startTestimonialAutoplay();
        });

        testimonialNext?.addEventListener('click', () => {
            renderTestimonialSlide((activeTestimonialSlide + 1) % testimonialSlides.length);
            startTestimonialAutoplay();
        });

        testimonialIndicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                renderTestimonialSlide(index);
                startTestimonialAutoplay();
            });
        });

        testimonialsSlider?.addEventListener('mouseenter', () => {
            clearInterval(testimonialIntervalId);
        });

        testimonialsSlider?.addEventListener('mouseleave', () => {
            startTestimonialAutoplay();
        });

        renderTestimonialSlide(0);
        startTestimonialAutoplay();
    }

    if (partnersSlider && partnersViewport && partnersTrack && partnerSlides.length > 0) {
        let activePartnerSlide = 0;
        let partnersIntervalId;

        const getPartnerOffset = () => {
            const activeSlide = partnerSlides[activePartnerSlide];
            const trackStyles = window.getComputedStyle(partnersTrack);
            const gap = Number.parseFloat(trackStyles.columnGap || trackStyles.gap || '0');
            const slideWidth = activeSlide.getBoundingClientRect().width;
            const viewportWidth = partnersViewport.getBoundingClientRect().width;
            const centeredOffset = (viewportWidth - slideWidth) / 2;

            return centeredOffset - (activePartnerSlide * (slideWidth + gap));
        };

        const renderPartnerSlide = (nextIndex) => {
            activePartnerSlide = (nextIndex + partnerSlides.length) % partnerSlides.length;

            const directionMultiplier = isRtl ? -1 : 1;
            partnersTrack.style.transform = `translate3d(${directionMultiplier * getPartnerOffset()}px, 0, 0)`;
        };

        const startPartnersAutoplay = () => {
            clearInterval(partnersIntervalId);

            if (prefersReducedMotion || partnerSlides.length < 2) {
                return;
            }

            partnersIntervalId = window.setInterval(() => {
                renderPartnerSlide(activePartnerSlide + 1);
            }, 3500);
        };

        partnersPrev?.addEventListener('click', () => {
            renderPartnerSlide(activePartnerSlide - 1);
            startPartnersAutoplay();
        });

        partnersNext?.addEventListener('click', () => {
            renderPartnerSlide(activePartnerSlide + 1);
            startPartnersAutoplay();
        });

        partnersSlider.addEventListener('mouseenter', () => {
            clearInterval(partnersIntervalId);
        });

        partnersSlider.addEventListener('mouseleave', () => {
            startPartnersAutoplay();
        });

        partnersSlider.addEventListener('focusin', () => {
            clearInterval(partnersIntervalId);
        });

        partnersSlider.addEventListener('focusout', () => {
            startPartnersAutoplay();
        });

        window.addEventListener('resize', () => {
            renderPartnerSlide(activePartnerSlide);
        });

        renderPartnerSlide(0);
        startPartnersAutoplay();
    }

    if ('IntersectionObserver' in window && revealElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) {
                    return;
                }

                entry.target.classList.remove('opacity-0');
                entry.target.classList.add('animate-fade-in-up');
                observer.unobserve(entry.target);
            });
        }, {
            threshold: 0.15,
        });

        revealElements.forEach((element) => observer.observe(element));
    } else {
        revealElements.forEach((element) => {
            element.classList.remove('opacity-0');
        });
    }
});
