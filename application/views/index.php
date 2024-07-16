<!DOCTYPE html>

<html lang="en">
	<!-- begin::Head -->
    <head>
        <meta charset="utf-8" />
        <title>Silver Silk</title>
        <meta name="description" content="HR Information System PT Vadhana International">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
        <!-- INCLUDE CSS HERE -->
        <?php include "include_css.php"; ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-131818181-4"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-131818181-4');


            function domReady(fn) {
                // If we're early to the party
                document.addEventListener("DOMContentLoaded", fn);
                // If late; I mean on time.
                if (document.readyState === "interactive" || document.readyState === "complete") {
                    fn();
                }
            }
        </script>
    </head>
    <!-- end::Head -->
	<!-- begin::Body -->
	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">

			<!-- BEGIN: Header -->
            <?php include "include_header.php"; ?>
			<!-- END: Header -->
             
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

            <!-- Content -->
            <?php include "include_leftmenu.php"; ?>

            <?php include "pages/" . $page_name . ".php"; ?>

            </div>
            <!-- Content -->

			<!-- begin::Footer -->
            <?php include "include_footer.php"; ?>
            <!-- end::Footer -->
		</div>

		<!-- end:: Page -->

		<!-- begin::Scroll Top -->
		<div id="m_scroll_top" class="m-scroll-top">
			<i class="la la-arrow-up"></i>
		</div>
		<!-- end::Scroll Top -->

		<!--begin::Base Scripts -->
        <?php include "include_js.php"; ?>
		<!--end::Page Vendors Scripts -->

	</body>
	<!-- end::Body -->
</html>