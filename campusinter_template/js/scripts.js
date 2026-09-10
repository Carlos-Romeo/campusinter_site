/**
 * ==============================================
 * Campus Inter - Template Joomla JavaScript Principal
 * Agence de mobilité internationale des étudiants
 * Design inspiré de FIGS Education
 * ==============================================
 */

(function() {
    'use strict';

    // ==============================================
    // 1. MENU MOBILE ET NAVIGATION
    // ==============================================
    
    const initMobileMenu = function() {
        const hamburgerBtn = document.querySelector('.hamburger-btn');
        const mainNav = document.querySelector('.main-nav');
        const body = document.body;
        
        // Créer l'overlay si nécessaire
        let navOverlay = document.querySelector('.nav-overlay');
        if (!navOverlay && mainNav) {
            navOverlay = document.createElement('div');
            navOverlay.className = 'nav-overlay';
            body.appendChild(navOverlay);
        }
        
        if (!hamburgerBtn || !mainNav) return;
        
        // Fonction pour ouvrir/fermer le menu
        const toggleMenu = function() {
            const isActive = hamburgerBtn.classList.contains('active');
            
            if (isActive) {
                closeMenu();
            } else {
                openMenu();
            }
        };
        
        const openMenu = function() {
            hamburgerBtn.classList.add('active');
            mainNav.classList.add('active');
            body.style.overflow = 'hidden';
            if (navOverlay) navOverlay.classList.add('active');
            hamburgerBtn.setAttribute('aria-expanded', 'true');
        };
        
        const closeMenu = function() {
            hamburgerBtn.classList.remove('active');
            mainNav.classList.remove('active');
            body.style.overflow = '';
            if (navOverlay) navOverlay.classList.remove('active');
            hamburgerBtn.setAttribute('aria-expanded', 'false');
        };
        
        // Événements
        hamburgerBtn.addEventListener('click', toggleMenu);
        
        if (navOverlay) {
            navOverlay.addEventListener('click', closeMenu);
        }
        
        // Fermer le menu au redimensionnement (desktop)
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                closeMenu();
            }
        });
        
        // Fermer le menu lors d'un clic sur un lien
        const navLinks = mainNav.querySelectorAll('.nav-link');
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                setTimeout(closeMenu, 300);
            });
        });
    };

    // ==============================================
    // 2. EFFET DE DÉFILEMENT SUR LE HEADER
    // ==============================================
    
    const initHeaderScroll = function() {
        const header = document.getElementById('site-header');
        if (!header) return;
        
        let lastScroll = 0;
        const scrollThreshold = 50;
        
        const handleScroll = function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > scrollThreshold) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            lastScroll = currentScroll;
        };
        
        // Throttle pour optimiser les performances
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
    };

    // ==============================================
    // 3. ANIMATIONS AU SCROLL (INTERSECTION OBSERVER)
    // ==============================================
    
    const initScrollAnimations = function() {
        // Vérifier si Intersection Observer est supporté
        if (!('IntersectionObserver' in window)) {
            // Fallback : afficher tous les éléments
            const animatedElements = document.querySelectorAll('[data-aos]');
            animatedElements.forEach(function(el) {
                el.classList.add('aos-animate');
            });
            return;
        }
        
        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -50px 0px',
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animate');
                    // Ne pas observer à nouveau pour des performances optimales
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Observer tous les éléments avec data-aos
        const animatedElements = document.querySelectorAll('[data-aos]');
        animatedElements.forEach(function(el) {
            observer.observe(el);
        });
    };

    // ==============================================
    // 4. COMPTEURS ANIMÉS (STATISTIQUES)
    // ==============================================
    
    const initCounters = function() {
        const counters = document.querySelectorAll('.stat-number[data-count]');
        if (counters.length === 0) return;
        
        // Vérifier si Intersection Observer est supporté
        if (!('IntersectionObserver' in window)) {
            counters.forEach(function(counter) {
                animateCounter(counter);
            });
            return;
        }
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(function(counter) {
            observer.observe(counter);
        });
    };
    
    const animateCounter = function(counter) {
        const target = parseInt(counter.getAttribute('data-count'), 10);
        const duration = 2000; // 2 secondes
        const startTime = performance.now();
        const startValue = 0;
        
        const easeOutQuart = function(t) {
            return 1 - Math.pow(1 - t, 4);
        };
        
        const updateCounter = function(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeProgress = easeOutQuart(progress);
            const currentValue = Math.floor(startValue + (target - startValue) * easeProgress);
            
            counter.textContent = currentValue.toLocaleString('fr-FR');
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString('fr-FR');
            }
        };
        
        requestAnimationFrame(updateCounter);
    };

    // ==============================================
    // 5. FAQ ACCORDÉON
    // ==============================================
    
    const initFAQ = function() {
        const faqQuestions = document.querySelectorAll('.faq-question');
        
        faqQuestions.forEach(function(question) {
            question.addEventListener('click', function() {
                const faqItem = this.parentElement;
                const isActive = faqItem.classList.contains('active');
                
                // Fermer tous les autres items
                const allItems = document.querySelectorAll('.faq-item');
                allItems.forEach(function(item) {
                    item.classList.remove('active');
                });
                
                // Ouvrir l'item cliqué (s'il était fermé)
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });
    };

    // ==============================================
    // 6. MODALE PARTENAIRES
    // ==============================================
    
    const partnerDescriptions = {
        galileo: {
            title: 'Galileo Global Education',
            tagline: 'Leader mondial de l\'enseignement supérieur privé',
            description: '<p>Galileo Global Education (GGE) est le leader mondial de l\'enseignement supérieur privé. Le groupe regroupe des écoles de commerce et d\'ingénieurs de renom international, offrant une palette exceptionnelle de formations diplômantes et reconnues.</p>',
            schools: [
                'INSEEC (Institut Supérieur du Commerce et de l\'Entreprise)',
                'EBS Paris (European Business School)',
                'ESCE (École Supérieure du Commerce Extérieur)',
                'Et bien d\'autres établissements prestigieux à travers le monde'
            ]
        },
        mediaschool: {
            title: 'MediaSchool',
            tagline: '14 écoles, 29 campus en France',
            description: '<p>MediaSchool est un groupe d\'enseignement supérieur privé français regroupant 14 écoles et 29 campus, avec 13 500 entreprises partenaires. Le groupe est spécialisé dans les métiers des médias, de la communication, du journalisme, du commerce et du design.</p>',
            schools: [
                'IEJ (Institut Européen de Journalisme)',
                'ECS (École de Commerce et de Stratégie)',
                'IRIS (Institut de Réalité Informatique et de Stratégie)',
                'Paris School of Luxury',
                'Autres écoles spécialisées dans les médias et le digital'
            ]
        },
        omnes: {
            title: 'OMNES Éducation',
            tagline: '15 grandes écoles, 40 000 étudiants',
            description: '<p>Anciennement INSEEC U., OMNES Éducation regroupe 15 grandes écoles en France et à l\'international, accueillant plus de 40 000 étudiants. Le groupe propose des formations de haut niveau dans les domaines du commerce, des sciences politiques, des relations internationales et des sciences humaines.</p>',
            schools: [
                'INSEEC (Institut Supérieur du Commerce et de l\'Entreprise)',
                'ESCE (École Supérieure du Commerce Extérieur)',
                'EU Business School (Genève)',
                'HEIP (Hautes Études Internationales et Politiques)',
                'Et 11 autres écoles d\'excellence'
            ]
        },
        mbn: {
            title: 'MBN Global Education',
            tagline: 'Formation supérieure en alternance et international',
            description: '<p>MBN Global Education est un groupe de formation supérieure regroupant environ 6 structures en France et au Cameroun. Le groupe est axé sur l\'alternance et l\'international, proposant des formations adaptées aux besoins du marché du travail.</p>',
            schools: [
                'EMSP Business School (BTS, Bachelor, Mastère)',
                'Autres établissements du groupe MBN en France et au Cameroun',
                'Programmes en alternance et formations professionnalisantes'
            ]
        },
        hema: {
            title: 'Groupe HEMA',
            tagline: 'Premier groupe 100 % alternance en France',
            description: '<p>Le Groupe HEMA (Haut Enseignement du Management en Alternance) est le premier groupe français 100 % alternance. Il regroupe 5 écoles spécialisées dans la formation par l\'alternance, offrant des parcours professionnalisants et reconnus.</p>',
            schools: [
                'ESM-A (École Supérieure de Management en Alternance)',
                'ESCI (École Supérieure de Commerce et d\'Informatique)',
                'ISEADD (Institut Supérieur d\'Études Alternatives et de Développement Durable)',
                'Deux autres écoles spécialisées dans l\'alternance'
            ]
        }
    };
    
    const initPartnerModal = function() {
        const modal = document.getElementById('partner-modal');
        const modalBody = document.getElementById('modal-body');
        const modalClose = document.querySelector('.modal-close');
        
        if (!modal || !modalBody || !modalClose) return;
        
        // Ouvrir la modale au clic sur une carte partenaire
        const partnerCards = document.querySelectorAll('.partner-card');
        
        partnerCards.forEach(function(card) {
            card.addEventListener('click', function() {
                const partnerKey = this.getAttribute('data-partner');
                const partnerData = partnerDescriptions[partnerKey];
                
                if (!partnerData) return;
                
                // Construire le contenu de la modale
                let content = '<h3>' + partnerData.title + '</h3>';
                content += '<p class="modal-tagline">' + partnerData.tagline + '</p>';
                content += partnerData.description;
                content += '<h4>Écoles et établissements associés :</h4>';
                content += '<ul>';
                partnerData.schools.forEach(function(school) {
                    content += '<li>' + school + '</li>';
                });
                content += '</ul>';
                
                modalBody.innerHTML = content;
                modal.classList.add('active');
                body.style.overflow = 'hidden';
            });
        });
        
        // Fermer la modale
        const closeModal = function() {
            modal.classList.remove('active');
            body.style.overflow = '';
        };
        
        modalClose.addEventListener('click', closeModal);
        
        // Fermer au clic sur l'overlay
        const modalOverlay = modal.querySelector('.modal-overlay');
        if (modalOverlay) {
            modalOverlay.addEventListener('click', closeModal);
        }
        
        // Fermer avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
    };

    // ==============================================
    // 7. DÉFILEMENT FLUIDE (SMOOTH SCROLL)
    // ==============================================
    
    const initSmoothScroll = function() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                
                // Ignorer les liens qui ne pointent pas vers un ID
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (!targetElement) return;
                
                e.preventDefault();
                
                const headerHeight = document.getElementById('site-header') ? 
                    document.getElementById('site-header').offsetHeight : 0;
                const targetPosition = targetElement.getBoundingClientRect().top + 
                    window.pageYOffset - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            });
        });
    };

    // ==============================================
    // 8. SURBRANCE DU LIEN ACTIF DANS LE MENU
    // ==============================================
    
    const initActiveNav = function() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        if (sections.length === 0 || navLinks.length === 0) return;
        
        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -80% 0px',
            threshold: 0
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const sectionId = entry.target.getAttribute('id');
                    
                    navLinks.forEach(function(link) {
                        link.parentElement.classList.remove('active');
                        if (link.getAttribute('href') === '#' + sectionId) {
                            link.parentElement.classList.add('active');
                        }
                    });
                }
            });
        }, observerOptions);
        
        sections.forEach(function(section) {
            observer.observe(section);
        });
    };

    // ==============================================
    // 9. INITIALISATION GLOBALE
    // ==============================================
    
    document.addEventListener('DOMContentLoaded', function() {
        initMobileMenu();
        initHeaderScroll();
        initScrollAnimations();
        initCounters();
        initFAQ();
        initPartnerModal();
        initSmoothScroll();
        initActiveNav();
        
        // Ajouter une classe au body pour indiquer que JS est chargé
        document.body.classList.add('js-enabled');
    });

    // ==============================================
    // 10. OPTIMISATIONS DE PERFORMANCE
    // ==============================================
    
    // Lazy loading pour les images (si supporté)
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(function(img) {
            img.src = img.dataset.src;
        });
    } else {
        // Fallback pour navigateurs plus anciens
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
        document.body.appendChild(script);
    }

})();
