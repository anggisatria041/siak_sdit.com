<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <?php
    $active_menu = in_array($page_name, [
        'V_dashboard',
    ]);
    ?>

    <!-- BEGIN: Aside Menu -->
     <!-- role admin -->
    <?php if ($this->session->userdata('hak_akses') == 'admin') : ?>
        <?php include 'include_leftmenu_admin.php'; ?>
     <!-- role guru -->
    <?php elseif ($this->session->userdata('hak_akses') == 'guru') : ?>
        <?php include 'include_leftmenu_guru.php'; ?>
    <!-- role orang tua -->
    <?php elseif ($this->session->userdata('hak_akses') == 'ortu') : ?>
        <?php include 'include_leftmenu_orangtua.php'; ?>
    <?php endif; ?>


     <!-- role orang tua -->
    
    <!-- END: Aside Menu -->
</div>

<!-- END: Left Aside -->