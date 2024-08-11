<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->userdata('hak_akses') == 'admin') : ?>
                <div class="m-portlet">
                    <div class="m-portlet__body  m-portlet__body--no-padding">
                        <div class="row m-row--no-padding m-row--col-separator-xl">
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Guru</h3>
                                                <span class="m-widget1__desc">Seluruh guru yang ada</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-info"><?= $guru ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Siswa</h3>
                                                <span class="m-widget1__desc">Seluruh Siswa yang ada</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-danger"><?= $siswa ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Staff</h3>
                                                <span class="m-widget1__desc">Seluruh Staff yang ada</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success">2</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Kelas</h3>
                                                <span class="m-widget1__desc">Seluruh Kelas yang ada</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"><?= $kelas ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Mata Pelajaran</h3>
                                                <span class="m-widget1__desc">Seluruh Mata Pelajaran yang ada</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"><?= $mapel ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Tahun Ajaran</h3>
                                                <span class="m-widget1__desc">Tahun Ajaran Aktif</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"><?= $tahun_ajaran->nama_tajaran ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif ($this->session->userdata('hak_akses') == 'guru') : ?>
                <div class="m-portlet">
                    <div class="m-portlet__body  m-portlet__body--no-padding">
                        <div class="row m-row--no-padding m-row--col-separator-xl">
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Kelas</h3>
                                                <span class="m-widget1__desc">Seluruh Kelas yang ada</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"><?= $kelas ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Mata Pelajaran</h3>
                                                <span class="m-widget1__desc">Seluruh Mata Pelajaran yang ada</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"><?= $mapel ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Tahun Ajaran</h3>
                                                <span class="m-widget1__desc">Tahun Ajaran Aktif</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"><?= $tahun_ajaran->nama_tajaran ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif ($this->session->userdata('hak_akses') == 'ortu') : ?>
                <div class="m-portlet">
                    <div class="m-portlet__body  m-portlet__body--no-padding">
                        <div class="row m-row--no-padding m-row--col-separator-xl">
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Kelas</h3>
                                                <span class="m-widget1__desc">Kelas yang diampuh</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"><?= $kelas->nama_kelas ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Mata Pelajaran</h3>
                                                <span class="m-widget1__desc">Seluruh Mata Pelajaran yang diampuh</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"><?= $mapel ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="m-widget1">
                                    <div class="m-widget1__item">
                                        <div class="row m-row--no-padding align-items-center">
                                            <div class="col">
                                                <h3 class="m-widget1__title">Tahun Ajaran</h3>
                                                <span class="m-widget1__desc">Tahun Ajaran Aktif</span>
                                            </div>
                                            <div class="col m--align-right">
                                                <span class="m-widget1__number m--font-success"><?= $tahun_ajaran->nama_tajaran ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-content">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="m-portlet m-portlet--full-height  ">
                                    <div class="m-portlet__body" id="m-portlet__body">
                                        <div class="m-card-profile">
                                            <div class="m-card-profile__details"  style="text-align: left;">
                                                <span class="m-card-profile__name"><?= $siswa_nis->nama ?></span>
                                                <hr />
                                                <span class="m-card-profile__keterangan"><b>NIS :</b><?= $siswa_nis->nis ?><br />
                                                <span class="m-card-profile__keterangan"><b>Tempat Lahir : </b><?= $siswa_nis->tempat_lahir ?><br>
                                                <span class="m-card-profile__keterangan"><b>No HP : </b><?= $siswa_nis->no_hp ?></span><br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <h3 class="m-portlet__head-text">
                                                    Akademik
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
                                                                Mata Pelajaran
                                                            </span>
                                                            <br>
                                                            <?php foreach ($mapel_nis as $us) : ?>
                                                                <span class="m-widget4__sub">
                                                                    <?= $us['nama_mapel']; ?>
                                                                </span><br>
                                                             <?php endforeach ?>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>