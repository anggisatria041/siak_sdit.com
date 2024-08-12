<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login | SIMA</title>
    <meta name="description" content="HR Information System | Vadhana International">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->

    <!--begin::Base Styles -->
    <link href="<?= base_url() ?>assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Base Styles -->
    <link rel="shortcut icon" href="<?= base_url() ?>assets/demo/default/media/img/logo/sdit.png" />

</head>

<body
    class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--ver-desktop m-grid--hor-tablet-and-mobile m-login m-login--6"
            id="m_login">
            <div class="m-grid__item   m-grid__item--order-tablet-and-mobile-2  m-grid m-grid--hor m-login__aside "
                style="background-image: url(<?= base_url() ?>assets/app/media/img/bg/bg-5.jpg);">
                <div class="m-grid__item">
                    <div class="m-login__logo">
                        <a href="" target="_blank">
                            <img src="<?php echo base_url(); ?>assets/demo/default/media/img/logo/sima2.png">
                        </a>
                    </div>
                </div>
                <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver">
                    <div class="m-grid__item m-grid__item--middle">
                        <span class="m-login__title">Sistem Akademik Sekolah Dasar IT Luhuring Budi</span>
                        <!--<span class="m-login__subtitle">&nbsp;</span>-->
                    </div>
                </div>
                <div class="m-grid__item">
                    <div class="m-login__info">
                        <div class="m-login__section">
                            <a href="#" class="m-link">&copy <?php echo date('Y'); ?> SD Luhuring Budi</a>
                        </div>
                        <div class="m-login__section">
                            <a href="" class="m-link" target="_blank">Official Website</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-grid__item m-grid__item--fluid  m-grid__item--order-tablet-and-mobile-1  m-login__wrapper">
                <!--begin::Body-->
                <div class="m-login__body">
                    <!--begin::Signin-->
                    <div class="m-login__signin">
                        <div class="m-login__title">
                            <h3>Sistem Akademik SD IT</h3>
                            <p>SD Luhuring Budi </p>
                        </div>
                        <!--begin::Form-->
                        <?php echo form_open('lawang/cekLogin', array('class' => 'm-login__form m-form', 'method' => 'POST', 'id' => 'formLogin')) ?>

                        <!-- MENAMPILKAN ALLERT -->
                        <?php if ($this->session->flashdata('alert')) { ?>
                            <div class="m-alert m-alert--outline m-alert--outline-2x alert alert-<?= $this->session->flashdata('alert') ?> alert-dismissible animated fadeIn"
                                role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                <span><?= $this->session->flashdata('message') ?></span>
                            </div>
                        <?php } ?>
                        <!--  -->
                        <input type="hidden" name="g-recaptcha_response" id="recaptchaResponse">

                        <div class="m-alert m-alert--outline alert alert-danger alert-capslock alert-dismissible animated fadeIn"
                            role="alert" style="display:none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                            <span>Warning! Caps Lock is on</span>
                        </div>

                        <!-- CEK HAKAKSES PENGGUNA -->
                        <?php if (!isset($hak_akses)) { ?>
                            <div class="form-group m-form__group">
                                <input class="form-control m-input" type="text" placeholder="Nis, Niy or Username"
                                    name="username" autocomplete="off">
                            </div>
                            <div class="form-group m-form__group">
                                <input class="form-control m-input m-login__form-input--last" type="password"
                                    placeholder="Password" name="password" onkeypress="capLock(event)">
                            </div>

                            <!--begin::Action-->
                            <div class="m-login__action" style="margin: 20px 0px 0px 0px;">
                                <a href="<?php echo base_url(); ?>lawang/forgot_password" class="m-link m--font-brand">
                                    <span>Forgot Password ?</span>
                                </a>
                                <a href="#">
                                    <button
                                        class="btn btn-info m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--info">Sign
                                        In</button>
                                </a>
                            </div>

                            <!--end::Action-->
                            </form>
                            <!--end::Form-->
                        <?php } else {
                            $x = md5('spadmin');
                            $y = md5('transport');
                            $c = md5('mekanik');
                            $w = md5('warehouse');
                            $f = md5('fuelman');
                            ?>
                            <div class="col-md-12">
                                <center>
                                    <?php if (isset($hak_akses['spadmin'])) { ?>
                                        <a href="<?= base_url() ?>lawang/auth/?auth=<?= $x ?>"
                                            class="btn btn-danger m-btn m-btn--pill m-btn--custom m-btn--air btn-block">
                                            Masuk Sebagai Spadmin <i class="la la-arrow-right"></i>
                                        </a>
                                        <hr />
                                    <?php } ?>
                                    <?php if (isset($hak_akses['admin_transport'])) { ?>
                                        <a href="<?= base_url() ?>lawang/auth/?auth=<?= $y ?>"
                                            class="btn btn-info m-btn m-btn--pill m-btn--custom m-btn--air btn-block">
                                            Masuk Sebagai Admin Transport <i class="la la-arrow-right"></i>
                                        </a>
                                        <hr />
                                    <?php } ?>
                                    <?php if (isset($hak_akses['admin_mekanik'])) { ?>
                                        <a href="<?= base_url() ?>lawang/auth/?auth=<?= $c ?>"
                                            class="btn btn-brand m-btn m-btn--pill m-btn--custom m-btn--air btn-block">
                                            Masuk Sebagai Admin Mekanik <i class="la la-arrow-right"></i>
                                        </a>
                                        <hr />
                                    <?php } ?>
                                    <?php if (isset($hak_akses['admin_warehouse'])) { ?>
                                        <a href="<?= base_url() ?>lawang/auth/?auth=<?= $w ?>"
                                            class="btn m-btn--pill m-btn m-btn--gradient-from-success m-btn--gradient-to-accent btn-block">
                                            Masuk Sebagai Admin Warehouse <i class="la la-arrow-right"></i>
                                        </a>
                                        <hr />
                                    <?php } ?>
                                    <?php if (isset($hak_akses['admin_fuelman'])) { ?>
                                        <a href="<?= base_url() ?>lawang/auth/?auth=<?= $f ?>"
                                            class="btn m-btn--pill m-btn m-btn--gradient-from-success m-btn--gradient-to-accent btn-block">
                                            Masuk Sebagai Mini Warehouse <i class="la la-arrow-right"></i>
                                        </a>
                                        <hr />
                                    <?php } ?>
                                </center>
                            </div>
                        <?php } ?>
                    </div>
                    <!--end::Signin-->
                </div>
                <!--end::Body-->
            </div>
        </div>
    </div>
    <!-- end:: Page -->
    <!--begin::Base Scripts -->
    <script src="<?= base_url() ?>assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
    <!--end::Base Scripts -->
    <!--begin::Page Snippets -->
    <script src="<?= base_url() ?>assets/snippets/custom/pages/user/login.js" type="text/javascript"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= $recaptcha ?>"></script>
    <script>
        function capLock(e) {
            kc = e.keyCode ? e.keyCode : e.which;
            sk = e.shiftKey ? e.shiftKey : ((kc == 1) ? true : false);
            if (((kc >= 65 && kc <= 90) && !sk) || ((kc >= 97 && kc <= 122) && sk))
                $('.alert-capslock').show();
            else
                $('.alert-capslock').hide();
        }
    </script>
    <script type="text/javascript">
        $('#formLogin').on('submit', function (e) {
            e.preventDefault();
            grecaptcha.execute("<?= $recaptcha ?>", {
                action: 'lawang'
            }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
                document.getElementById('formLogin').submit();
            });
        });
    </script>
    <!--end::Page Snippets -->
</body>

</html>