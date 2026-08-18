(function() {
    'use strict';

    function initProfileDropdown() {
        const wrap = document.getElementById('dashUserWidgetWrap');
        const btn = document.getElementById('dashUserWidgetBtn');
        const dropdown = document.getElementById('dashProfileDropdown');
        const fileInput = document.getElementById('profileAvatarInput');
        const btnOpenFull = document.getElementById('btnOpenFullAvatar');
        const avatarModal = document.getElementById('profileAvatarModal');
        const closeAvatarModalBtn = document.getElementById('closeProfileAvatarModal');

        if (!wrap || !btn || !dropdown) {
            return;
        }

        function openDropdown() {
            dropdown.hidden = false;
            btn.setAttribute('aria-expanded', 'true');
        }

        function closeDropdown() {
            dropdown.hidden = true;
            btn.setAttribute('aria-expanded', 'false');
        }

        function openAvatarModal() {
            if (avatarModal) {
                avatarModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeAvatarModal() {
            if (avatarModal) {
                avatarModal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        // Open/Close Dropdown
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (dropdown.hidden) {
                openDropdown();
            } else {
                closeDropdown();
            }
        });

        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        document.addEventListener('click', closeDropdown);

        // Open Full View Modal when clicking avatar photo
        if (btnOpenFull) {
            btnOpenFull.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                openAvatarModal();
            });
        }

        if (closeAvatarModalBtn) {
            closeAvatarModalBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                closeAvatarModal();
            });
        }

        if (avatarModal) {
            avatarModal.addEventListener('click', function(e) {
                if (e.target === avatarModal) {
                    closeAvatarModal();
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDropdown();
                closeAvatarModal();
            }
        });

        // Update avatar image preview live for both small & fullview
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(ev) {
                    const smallPreview = document.getElementById('profileAvatarPreview');
                    const fullPreview = document.getElementById('fullViewAvatarImg');

                    // Update small preview in dropdown
                    if (smallPreview) {
                        if (smallPreview.tagName === 'IMG') {
                            smallPreview.src = ev.target.result;
                        } else {
                            const img = document.createElement('img');
                            img.id = 'profileAvatarPreview';
                            img.alt = 'Pratinjau foto';
                            img.className = 'dash-profile-photo';
                            img.src = ev.target.result;
                            smallPreview.replaceWith(img);
                        }
                    }

                    // Update full view preview in modal
                    if (fullPreview) {
                        if (fullPreview.tagName === 'IMG') {
                            fullPreview.src = ev.target.result;
                        } else {
                            const img = document.createElement('img');
                            img.id = 'fullViewAvatarImg';
                            img.alt = 'Foto profil';
                            img.style.cssText = 'max-width: 85vw; max-height: 80vh; width: 340px; height: 340px; border-radius: 24px; object-fit: cover; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); border: 2px solid rgba(255, 255, 255, 0.2);';
                            img.src = ev.target.result;
                            fullPreview.replaceWith(img);
                        }
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        const successAlert = dropdown.querySelector('.dash-profile-alert.success');
        if (successAlert) {
            openDropdown();
            successAlert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            setTimeout(function() {
                successAlert.style.opacity = '0';
                successAlert.style.transform = 'translateY(-6px)';
                setTimeout(function() {
                    successAlert.remove();
                }, 500);
            }, 3000);
        } else if (dropdown.querySelector('.dash-profile-alert')) {
            openDropdown();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProfileDropdown);
    } else {
        initProfileDropdown();
    }
})();
