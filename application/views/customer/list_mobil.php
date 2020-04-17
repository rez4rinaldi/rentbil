
<!--== Page Title Area Start ==-->
<section id="page-title-area" class="section-padding overlay">
    <div class="container">
        <div class="row">
            <!-- Page Title Start -->
            <div class="col-lg-12">
                <div class="section-title  text-center">
                    <h2>List Mobil</h2>
                    <span class="title-line"><i class="fa fa-car"></i></span>
                    <p>Daftar List Mobil Pada Rental Kami.</p>
                </div>
            </div>
            <!-- Page Title End -->
        </div>
    </div>
</section>
<!--== Page Title Area End ==-->

<!--== Car List Area Start ==-->
<section id="car-list-area" class="section-padding">
    <div class="container">
        <div class="row">
            <!-- Car List Content Start -->
            <div class="col-lg-12">
                <div class="car-list-content">
                    <div class="row">
                        <!-- Single Car Start -->
                        <?php foreach ($mobil as $mb) : ?>
                            <div class="col-lg-6 col-md-6">
                                <div class="single-car-wrap">
                                    <div class="car-list-thumb">
                                        <img src="<?= base_url() . 'assets/upload/mobil/' . $mb->gambar ?>" style="height: 300px; width: 540px">
                                    </div>
                                    <div class="car-list-info without-bar">
                                        <h2><a href="<?= base_url('customer/dashboard/detail_mobil/') . $mb->id_mobil ?>"><?= $mb->merk ?></a></h2>
                                        <h5 style="color: #014782"><?= format_rupiah($mb->harga) ?> / hari</h5>
                                        <ul class="car-info-list">
                                            <li><i class="fa fa-check-square" style="color: #014782"></i> AC</li>
                                            <li><i class="fa fa-times-circle text-danger"></i> Supir</li>
                                            <li><i class="fa fa-check-square" style="color: #014782"></i> Audio Player</li>
                                            <li><i class="fa fa-times-circle text-danger"></i> Central Lock</li>
                                        </ul>
                                        <p class="rating">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star unmark"></i>
                                        </p>
                                        <?php if ($mb->status == 1) { ?>
                                            <a href="<?= base_url('customer/rental/tambah_rental/') . $mb->id_mobil ?>" class="rent-btn"><i class="fa fa-car text-warning"></i> Sewa</a>
                                            <a href="<?= base_url('customer/dashboard/detail_mobil/') . $mb->id_mobil ?>" class="rent-btn"><i class="fa fa-search-plus text-success"></i> Detail</a>
                                        <?php } else { ?>
                                            <a href="javascript:;" class="rent-btn"><i class="fa fa-times-circle text-danger"></i> Disewa</a>
                                            <a href="<?= base_url('customer/dashboard/detail_mobil/') . $mb->id_mobil ?>" class="rent-btn"><i class="fa fa-search-plus text-success"></i> Detail</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- Single Car End -->
                    </div>
                </div>
            </div>
            <!-- Car List Content End -->
        </div>
    </div>
</section>
<!--== Car List Area End ==-->