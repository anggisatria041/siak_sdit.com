<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->

<head>
    <meta charset="utf-8" />
    <title>Forgot Password | Vadhana International</title>
    <meta name="description" content="Information System | Vadhana International">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->

    <!--begin::Base Styles -->
    <link href="<?= base_url() ?>assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />
    <!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
    <!--end::Base Styles -->
    <link rel="shortcut icon" href="<?= base_url() ?>assets/app/media/img/logos/logo.png" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-131818181-4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-131818181-4');
    </script>
</head>
<!-- end::Head -->
<!-- begin::Body -->

<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--ver-desktop m-grid--hor-tablet-and-mobile m-login m-login--6" id="m_login">
            <div class="m-grid__item   m-grid__item--order-tablet-and-mobile-2  m-grid m-grid--hor m-login__aside " style="background-image: url(<?= base_url() ?>assets/app/media/img/bg/bg-2.jpg);">
                <div class="m-grid__item">
                    <div class="m-login__logo">
                        <a href="http://vadhana.co.id" target="_blank">
                            <img src="<?= base_url() ?>assets/app/media/img/logos/logo.png">
                        </a>
                    </div>
                </div>
                <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver">
                    <div class="m-grid__item m-grid__item--middle">
                        <span class="m-login__title">Transport Management System</span>
                        <!--<span class="m-login__subtitle">&nbsp;</span>-->
                    </div>
                </div>
                <div class="m-grid__item">
                    <div class="m-login__info">
                        <div class="m-login__section">
                            <a href="#" class="m-link">&copy <?php echo date("Y"); ?> Vadhana International</a>
                        </div>
                        <div class="m-login__section">
                            <a href="http://vadhana.co.id" class="m-link">Official Website</a>
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
                            <h3>Forgot Password</h3>
                        </div>
                        <!--begin::Form-->

                        <?php echo form_open('lawang/forgot_password/reset', array('class' => 'm-login__form m-form', 'method' => 'POST', 'id' => 'formLogin')) ?>

                        <input type="hidden" name="g-recaptcha_response" id="recaptchaResponse">

                        <!-- MENAMPILKAN ALERT -->
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="m-alert m-alert--outline m-alert--outline-2x alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                </button>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>

                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="m-alert m-alert--outline m-alert--outline-2x alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                </button>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>

                        <?php } ?>

                        <div class="form-group m-form__group">
                            <input class="form-control m-input" type="text" placeholder="NIK or Email" name="id" autocomplete="off">
                        </div>

                        <!--begin::Action-->
                        <div class="m-login__action" style="margin: 20px 0px 0px 0px;">
                            <a href="<?php echo base_url(); ?>lawang" class="m-link m--font-brand">
                                <span>Back to Login</span>
                            </a>
                            <a href="#">
                                <button class="btn btn-brand m-btn  m-login__btn m-login__btn--primary">Reset</button>
                            </a>
                        </div>
                        <!--end::Action-->

                        </form>
                        <!--end::Form-->
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
    <!--end::Page Snippets -->
    <script src="https://www.google.com/recaptcha/api.js?render=<?= $recaptcha ?>"></script>
    <script>
        $('#formLogin').on('submit', function(e) {
            e.preventDefault();
            grecaptcha.execute("<?= $recaptcha ?>", {
                action: 'lawang'
            }).then(function(token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
                document.getElementById('formLogin').submit();
            });
        });
    </script>
</body>
<!-- end::Body -->

</html>