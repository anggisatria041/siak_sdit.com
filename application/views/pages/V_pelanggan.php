<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                   Pelanggan
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Pelanggan
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->

    <!-- END: Subheader -->
    <div class="m-content">
        <div class="row">
            <div class="col-md-12">
                <div class="m-portlet m-portlet--head-solid-bg m-portlet--bordered m-portlet--brand">
                    <div class="m-portlet__head ">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Manage Data Pelanggan<p id="hax"></p>
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <button type="button" class="btn btn-info btn-md">
                                <i class="la la-plus"></i> Tambah Pelanggan
                            </button>
                        </div>
                    </div>
                     <div class="m-portlet__body">                                
                        <?php if ($this->session->flashdata('alert1')) { ?>
                            <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-<?= $this->session->flashdata('alert1') ?> alert-dismissible fade show" role="alert">
                                <div class="m-alert__icon">
                                    <i class="flaticon-exclamation-1"></i>
                                    <span></span>
                                </div>
                                <div class="m-alert__text">
                                    <?= $this->session->flashdata('alert2') ?>
                                </div>
                                <div class="m-alert__close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        <?php } ?>
                        <!--begin: Datatable -->
                        <ul class="nav nav-pills nav-fill" role="tablist">
                            <li class="nav-item col-6">
                                <a class="nav-link active" data-toggle="tab" onclick="renew('tableManage')" href="#m_tabs_5_1"><b>TICKET BOOK</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" onclick="renew('tableManage2')" href="#m_tabs_5_2"><b>TICKET</b></a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="m_tabs_5_1" role="tabpanel">
                                <div class="m_datatable" id="tableManage"></div>
                            </div>
                            <div class="tab-pane" id="m_tabs_5_2" role="tabpanel">
                                <div class="m_datatable" id="tableManage2"></div>
                            </div>
                        </div>
                        <!--end: Datatable -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Body -->
