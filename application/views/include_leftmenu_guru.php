<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1"
    m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
    <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
        <li class="m-menu__item <?= $active_menu ? 'm-menu__item--active' : '' ?>" aria-haspopup="true">
            <a href="<?= base_url() . 'C_dashboard' ?>" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-line-graph"></i>
                <span class="m-menu__link-title">
                    <span class="m-menu__link-wrap">
                        <span class="m-menu__link-text">Dashboard</span>
                    </span>
                </span>
            </a>
        </li>
        
        <li class="m-menu__item <?= $page_name == "V_absensi" ? "m-menu__item--active" : "" ?>" aria-haspopup="true"
            m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_absensi'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-network"></i>
                <span class="m-menu__link-text">Absensi</span>
            </a>
        </li>
        <li class="m-menu__item <?= ($page_name == "V_nilai" || $page_name == "V_mata_pelajaran" || $page_name == "V_input_nilai" || $page_name == "V_rekap_nilai") ? "m-menu__item--active" : "" ?>"
            aria-haspopup="true" m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_nilai'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-diagram"></i>
                <span class="m-menu__link-text">Nilai</span>
            </a>
        </li>
        
    </ul>
</div>