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
        <li class="m-menu__item <?= $page_name == "V_siswa" ? "m-menu__item--active" : "" ?>" aria-haspopup="true"
            m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_siswa'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-list-2"></i>
                <span class="m-menu__link-text">Siswa</span>
            </a>
        </li>
        <li class="m-menu__item <?= $page_name == "V_guru" ? "m-menu__item--active" : "" ?>" aria-haspopup="true"
            m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_guru'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-list-2"></i>
                <span class="m-menu__link-text">Guru</span>
            </a>
        </li>
        <li class="m-menu__item <?= $page_name == "V_mapel" ? "m-menu__item--active" : "" ?>" aria-haspopup="true"
            m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_mapel'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-list-2"></i>
                <span class="m-menu__link-text">Mata Pelajaran</span>
            </a>
        </li>
        <li class="m-menu__item <?= $page_name == "V_akun" ? "m-menu__item--active" : "" ?>" aria-haspopup="true"
            m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_akun'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-list-2"></i>
                <span class="m-menu__link-text">Akun</span>
            </a>
        </li>
        <li class="m-menu__item <?= $page_name == "V_pembayaran" ? "m-menu__item--active" : "" ?>" aria-haspopup="true"
            m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_pembayaran'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-list-2"></i>
                <span class="m-menu__link-text">Pembayaran</span>
            </a>
        </li>
        <li class="m-menu__item <?= $page_name == "V_tahun_ajaran" ? "m-menu__item--active" : "" ?>"
            aria-haspopup="true" m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_tahun_ajaran'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-list-2"></i>
                <span class="m-menu__link-text">Tahun Ajaran</span>
            </a>
        </li>
        <li class="m-menu__item <?= $page_name == "V_absensi" ? "m-menu__item--active" : "" ?>" aria-haspopup="true"
            m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_absensi'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-list-2"></i>
                <span class="m-menu__link-text">Absensi</span>
            </a>
        </li>
        <li class="m-menu__item <?= ($page_name == "V_nilai" || $page_name == "V_mata_pelajaran" || $page_name == "V_input_nilai" || $page_name == "V_rekap_nilai") ? "m-menu__item--active" : "" ?>"
            aria-haspopup="true" m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_nilai'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-list-2"></i>
                <span class="m-menu__link-text">Nilai</span>
            </a>
        </li>
        <li class="m-menu__item <?= $page_name == "V_orang_tua" ? "m-menu__item--active" : "" ?>" aria-haspopup="true"
            m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_orang_tua'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-list-2"></i>
                <span class="m-menu__link-text">Orang Tua</span>
            </a>
        </li>
        <li class="m-menu__item <?= $page_name == "V_kelas" ? "m-menu__item--active" : "" ?>" aria-haspopup="true"
            m-menu-link-redirect="1">
            <a href="<?= site_url('dir/C_kelas'); ?>" class="m-menu__link ">
                <span class="m-menu__item-here"></span>
                <i class="m-menu__link-icon flaticon-list-2"></i>
                <span class="m-menu__link-text">Kelas</span>
            </a>
        </li>
    </ul>
</div>