<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                   Nilai
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
                                Nilai
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
                                    Manage Data Nilai<p id="hax"></p>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">                                
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                            <?php foreach ($kelas as $row) : ?>
                            <tr>
                                <td style="width: 30%"><?= $row->nama_kelas; ?></td>
                                <td>
                                    <a  href="<?php echo base_url() ?>dir/C_nilai/mata_pelajaran/tambah/<?php echo encrypt($row->kelas_id); ?>" class="btn btn-info" >Input Nilai</a>
                                    <a href="<?php echo base_url() ?>dir/C_nilai/mata_pelajaran/rekap/<?php echo encrypt($row->kelas_id); ?>" class="btn btn-success">Rekap Nilai</a>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Body -->
