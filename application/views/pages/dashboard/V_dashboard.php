<div class="m-grid__item m-grid__item--fluid m-wrapper">

    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">Dashboard</h3>
            </div>
            <div class="col-md-4" style="margin-top: 0.10rem !important;">
            </div>
        </div>
    </div>
    <!-- END: Subheader -->

    <div class="m-content">
        <!--begin:: Widgets/ Data Pertahun-->
        <div class="row">
            <div class="col-lg-3">
                <div class="m-portlet m-portlet--full-height  ">
                    <div class="m-portlet__body" id="m-portlet__body">
                        <div class="m-card-profile">
                            <div class="m-card-profile__details"  style="text-align: left;">
                                <span class="m-card-profile__name"  style="text-align: center;">Dashboard</span>
                                <hr />
                                <span class="m-card-profile__keterangan"><b>Lokasi : </b><br />
                                <span class="m-card-profile__keterangan"><b>Produk BBM : </b><br>
                                <span class="m-card-profile__keterangan"><b>Kepala Warehouse : </b></span><br>
                            </div>
                        </div>
                        <ul class="m-nav m-nav--hover-bg m-portlet-fit--sides">
                            <center>
                                
                            </center>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Warehouse
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="m-widget4">
                                    <a class="m-widget4__item">
                                        <div class="m-widget4__info">
                                            <span class="m-widget4__title">
                                                Semua Fuel Ticket
                                            </span>
                                            <br>
                                            <span class="m-widget4__sub">
                                                Belum Diajukan
                                            </span><br>
                                             <span class="m-widget4__sub">
                                                Sudah Diajukan
                                            </span><br>
                                        </div>
                                        <span class="m-widget4__ext">
                                            <span class="m-widget4__number m--font-brand">&nbsp;</span>
                                            <span class="m-widget4__sub">&nbsp;</span><br>
                                            <span class="m-widget4__sub">&nbsp;</span><br>
                                        </span>
                                    </a>
                                    <a  class="m-widget4__item">
                                        <div class="m-widget4__info">
                                            <span class="m-widget4__title">
                                                Metode Input
                                            </span>
                                            <br>
                                            <span class="m-widget4__sub">
                                                Web
                                            </span><br>
                                            <span class="m-widget4__sub">
                                               Mobile
                                            </span>
                                        </div>
                                        <span class="m-widget4__ext">
                                            <span class="m-widget4__number m--font-info">&nbsp;</span>
                                            <span class="m-widget4__sub">&nbsp;</span><br>
                                            <span class="m-widget4__sub">&nbsp;</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="m-widget4">
                                    <a class="m-widget4__item">
                                        <div class="m-widget4__info">
                                            <span class="m-widget4__title">
                                                Jumlah BBM Digunakan Bulan Ini
                                            </span><br><br>
                                              <span class="m-widget4__title">
                                                Jumlah BBM Digunakan Bulan Lalu
                                            </span>
                                        </div>
                                        <span class="m-widget4__ext">
                                            <span class="m-widget4__number m--font-success">&nbsp;</span>
                                            <span class="m-widget4__number m--font-danger">&nbsp;</span>
                                        </span>
                                    </a>
                                    <a class="m-widget4__item">
                                        <div class="m-widget4__info">
                                            <span class="m-widget4__title">
                                                Jumlah BBM Belum Dilaporkan
                                            </span><br><br>
                                            <span class="m-widget4__title">
                                                Jumlah BBM Sudah Dilaporkan
                                            </span>
                                        </div>
                                        <span class="m-widget4__ext">
                                            <span class="m-widget4__number m--font-brand">&nbsp;</span>
                                            <span class="m-widget4__number m--font-info">&nbsp;</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Aktivitas Terbaru (Log)-->
        <div class="m-portlet m-portlet--head-solid-bg m-portlet--bordered m-portlet--danger">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Aktivitas Terbaru
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="col-md-12 m--bg-light ">
                    <div class="m-scrollable" data-scrollable="true" data-max-height="400" style="height: 400px; overflow: hidden;">

                        <div class="m-list-timeline m-list-timeline--skin-light">
                            <div class="m-list-timeline__items">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End LOG -->
    </div>
</div>
<!-- end:: Body -->

<!-- Javascript untuk membuat Grafik -->
<script src="<?= base_url() ?>assets/amchart4/core.js"></script>
<script src="<?= base_url() ?>assets/amchart4/charts.js"></script>
<script src="<?= base_url() ?>assets/amchart4/themes/animated.js"></script>
<!-- -->
<!-- Styles -->
<!-- <script>
    document.getElementById("judulBar").innerHTML += " Tahun " + document.getElementById("tahun").value;
</script> -->
<!-- Styles -->
<style>
    #chartdiv {
        width: 100%;
        height: 500px;
    }
</style>