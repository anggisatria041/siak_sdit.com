<?php
defined('BASEPATH') or exit('No direct script access allowed');

// fungsi ini menghitung (MR, PR, PO, MI, RMO, MTin) pending
function get_incoming_stock(int $warehouse_id, int $itemdetail_id)
{
    $CI = get_instance();
    $CI->load->model('Md_itemdetail');
    $CI->load->model('Md_miodetail');

    $items = $CI->Md_itemdetail->getIncomingStock($warehouse_id, $itemdetail_id);

    $data = [];
    // Mapping MR dan PR
    foreach ($items as $value) {
        $data[$value->mrid]['mr_idid'] = $value->mr_idid;
        $data[$value->mrid]['mr_qty']  = $value->mr_qty;
        if ($value->prid != '') {
            $data[$value->mrid]['pr'] = [
                'prid'          => $value->prid,
                'pr_idid'       => $value->pr_idid,
                'pr_statusitem' => $value->pr_statusitem,
                'pr_statusaksi' => $value->pr_statusaksi,
                'pr_qty'        => $value->pr_qty,
                'pr_qtypo'      => $value->pr_qtypo,
            ];
        }
    }

    // Mapping PO
    foreach ($data as $mrid => $mr) {
        if (!isset($data[$mrid]['pr'])) continue;

        foreach ($items as $po) {
            if ($po->statuspo == 'Cancel') continue;

            if ($po->prid == $mr['pr']['prid'] && $po->poid != '') {
                $data[$mrid]['pr']['po'][] = [
                    'poid'          => $po->poid,
                    'po_idid'       => $po->po_idid,
                    'po_statusitem' => $po->po_statusitem,
                    'po_qty'        => $po->po_qty,
                    'po_qtymi'      => $po->po_qtymi,
                ];
            }
        }
    }

    // Mapping MI
    foreach ($data as $mrid => $mr) {
        if (!isset($mr['pr']['po'])) continue;

        foreach ($mr['pr']['po'] as $key => $po) {
            foreach ($items as $mi) {

                if ($mi->mioid != '') {
                    if ($mi->poid == $po['poid']) {
                        $data[$mrid]['pr']['po'][$key]['mi'][] = [
                            'mioid'             => $mi->mioid,
                            'mi_idid'           => $mi->mi_idid,
                            'mi_statusapproval' => $mi->mi_statusapproval,
                            'mi_qty'            => $mi->mi_qty,
                        ];
                    }
                }
            }
        }
    }

    $stock = 0;
    foreach ($data as $mr) {
        $has_pr = isset($mr['pr']);
        if ($has_pr) {
            $has_po = isset($mr['pr']['po']);
            if ($has_po) {
                $qty_pr = $mr['pr']['pr_qty'];
                foreach ($mr['pr']['po'] as $po) {
                    $has_mi = isset($po['mi']);
                    if ($has_mi) {
                        // Hitung MI 
                        $qty_po = $po['po_qty'] - $po['po_qtymi']; // kurangi nilai mi yang sudah approve
                        foreach ($po['mi'] as $mi) {
                            if ($mi['mi_idid'] == $itemdetail_id && $mi['mi_idid'] != 'Approved') {
                                $stock += $mi['mi_qty'];
                            }

                            $qty_po -= $mi['mi_qty'];
                        }
                        $stock += $qty_po;
                    } else {
                        // Hitung PO
                        if ($po['po_idid'] == $itemdetail_id) {
                            $stock += $po['po_qty'];
                        }
                    }

                    $qty_pr -= $po['po_qty'];
                }
                // jika masih ada sisa pr yg belum po, maka tambahkan menjadi stok pending
                $stock += $qty_pr;
            } else {
                // Hitung PR
                if ($mr['pr']['pr_idid'] == $itemdetail_id && $mr['pr']['pr_statusaksi'] == 'Purchases') {
                    $stock += $mr['pr']['pr_qty'];
                }
            }
        } else {
            // Hitung MR
            $stock += $mr['mr_qty'];
        }
    }

    // Menghitung stok pending dari Retur MO
    $retur_mo = $CI->Md_miodetail->getPendingItemsRmo($warehouse_id, $itemdetail_id);

    // Menghitung stok pending dari MT In
    $MT_in = $CI->Md_miodetail->getPendingItemsMTin($warehouse_id, $itemdetail_id);

    $stock += $retur_mo->jumlah + $MT_in->jumlah;

    return $stock;
}

// fungsi ini menghitung (PO, MI, RMO, MTin) pending
function get_incoming_stock_for_po(int $warehouse_id, int $itemdetail_id)
{
    $CI = get_instance();
    $CI->load->model('Md_itemdetail');
    $CI->load->model('Md_miodetail');

    $items = $CI->Md_itemdetail->getIncomingStock($warehouse_id, $itemdetail_id);

    $data = [];
    // Mapping MR dan PR
    foreach ($items as $value) {
        $data[$value->mrid]['mr_idid'] = $value->mr_idid;
        $data[$value->mrid]['mr_qty']  = $value->mr_qty;
        if ($value->prid != '') {
            $data[$value->mrid]['pr'] = [
                'prid'          => $value->prid,
                'pr_idid'       => $value->pr_idid,
                'pr_statusitem' => $value->pr_statusitem,
                'pr_statusaksi' => $value->pr_statusaksi,
                'pr_qty'        => $value->pr_qty,
                'pr_qtypo'      => $value->pr_qtypo,
            ];
        }
    }

    // Mapping PO
    foreach ($data as $mrid => $mr) {
        if (!isset($data[$mrid]['pr'])) continue;

        foreach ($items as $po) {
            if ($po->statuspo == 'Cancel') continue;

            if ($po->prid == $mr['pr']['prid'] && $po->poid != '') {
                $data[$mrid]['pr']['po'][] = [
                    'poid'          => $po->poid,
                    'po_idid'       => $po->po_idid,
                    'po_statusitem' => $po->po_statusitem,
                    'po_qty'        => $po->po_qty,
                    'po_qtymi'      => $po->po_qtymi,
                ];
            }
        }
    }

    // Mapping MI
    foreach ($data as $mrid => $mr) {
        if (!isset($mr['pr']['po'])) continue;

        foreach ($mr['pr']['po'] as $key => $po) {
            foreach ($items as $mi) {

                if ($mi->mioid != '') {
                    if ($mi->poid == $po['poid']) {
                        $data[$mrid]['pr']['po'][$key]['mi'][] = [
                            'mioid'             => $mi->mioid,
                            'mi_idid'           => $mi->mi_idid,
                            'mi_statusapproval' => $mi->mi_statusapproval,
                            'mi_qty'            => $mi->mi_qty,
                        ];
                    }
                }
            }
        }
    }

    $stock = 0;
    foreach ($data as $mr) {
        $has_pr = isset($mr['pr']);
        if ($has_pr) {
            $has_po = isset($mr['pr']['po']);
            if ($has_po) {
                // $qty_pr = $mr['pr']['pr_qty'];
                foreach ($mr['pr']['po'] as $po) {
                    $has_mi = isset($po['mi']);
                    if ($has_mi) {
                        // Hitung MI 
                        $qty_po = $po['po_qty'] - $po['po_qtymi'];
                        foreach ($po['mi'] as $mi) {
                            if ($mi['mi_idid'] == $itemdetail_id && $mi['mi_idid'] != 'Approved') {
                                $stock += $mi['mi_qty'];
                            }

                            $qty_po -= $mi['mi_qty'];
                        }
                        $stock += $qty_po;
                    } else {
                        // Hitung PO
                        if ($po['po_idid'] == $itemdetail_id) {
                            $stock += $po['po_qty'];
                        }
                    }

                    // $qty_pr -= $po['po_qty'];
                }
                // jika masih ada sisa pr yg belum po, maka tambahkan menjadi stok pending
                // $stock += $qty_pr;
            } else {
                // Hitung PR
                if ($mr['pr']['pr_idid'] == $itemdetail_id && $mr['pr']['pr_statusaksi'] == 'Purchases') {
                    // $stock += $mr['pr']['pr_qty'];
                }
            }
        } else {
            // Hitung MR
            // $stock += $mr['mr_qty'];
        }
    }

    // Menghitung stok pending dari Retur MO
    $retur_mo = $CI->Md_miodetail->getPendingItemsRmo($warehouse_id, $itemdetail_id);

    // Menghitung stok pending dari MT In
    $MT_in = $CI->Md_miodetail->getPendingItemsMTin($warehouse_id, $itemdetail_id);

    $stock += $retur_mo->jumlah + $MT_in->jumlah;

    return $stock;
}

function make_mr_automatically(int $mioid, $sumber)
{
    $CI = get_instance();
    $CI->load->model('Md_itemdetail');
    $CI->load->model('Md_miodetail');
    $CI->load->model('Md_warehouse');
    $CI->load->model('Md_mio');
    $CI->load->model('Md_mr');
    $CI->load->model('Md_mrdetail');
    $CI->load->model('Md_teamanggota');
    $CI->load->model('Md_tokenmobile');
    $CI->load->model('Md_approval');
    $CI->load->model('Md_notifikasi');
    $CI->load->model('Md_mailbox');

    $mio       = $CI->Md_mio->getMoById($mioid);

    $warehouse = $CI->Md_warehouse->getWarehouseById($mio->warehouseid);

    if ($warehouse->isautorequest == 'Tidak') return 0;

    $itemdetail = $CI->Md_itemdetail->items_auto_request($mio->warehouseid, array_map(
        function ($row) {
            return $row->itemdetailid;
        },
        $CI->Md_miodetail->getDataBy([
            'mioid' => $mio->mioid
        ])
    ));

    if (count($itemdetail) == 0) return 0;

    $items_tobe_mr = [];
    $log_mr_min_max = [];
    foreach ($itemdetail as $key => $item) {
        $incoming_stock = get_incoming_stock($mio->warehouseid, $item->itemdetailid); //10

        // buat mr jika total req sudah sampai pada qty min
        if (($item->onhand + $incoming_stock) > $item->min) continue;

        $qty_request = $item->max - $item->onhand - $incoming_stock; //1
        if ($qty_request <= 0) continue;

        $brand       = $item->brand == '' ? "; - " : "; " . $item->brand;
        $size        = $item->size == '' ? "; - " : "; " . $item->size;
        $standar     = $item->standar == '' ? "; - " : "; " . $item->standar;
        $type        = $item->type == '' ? "; - " : "; " . $item->type;
        $spesifikasi = $item->spesifikasi == '' ? "; - " : "; " . $item->spesifikasi;
        $spesifikasiitem = "$item->itemdetailcode; $item->itemdetailname - $item->itemdetailnameen" . $brand . $size . $standar . $type . $spesifikasi;

        $data = [
            'itemid'          => $item->itemid,
            'itemdetailid'    => $item->itemdetailid,
            'qty'             => $qty_request,
            'satuanid'        => $item->satuanid,
            'statusapproval'  => 'Waiting',
            'statusitem'      => 'Check',
            'spesifikasiitem' => $spesifikasiitem,
            'status'          => 1,
        ];
        $items_tobe_mr[] = $data;

        $log_mr_min_max[] = [
            'sumber' => $sumber,
            'mioid' => $mioid,
            'mrdetail' => $data,
            'kontrol_stok' => [
                'min' => $item->min,
                'max' => $item->max,
                'onhand' => $item->onhand,
                'incoming' => $incoming_stock,
            ]
        ];
    }

    if (count($items_tobe_mr) == 0) return 0;

    if ($warehouse->teamprojectid == '') return 0;
    # Buat MR
    $nomr = get_nomor_mr(date('Y-m-d H:i:s'), $warehouse->perusahaanid, 'MR');

    $mr_id = $CI->Md_mr->addMR([
        'teamprojectid'  => $warehouse->teamprojectid,
        'jenisrequest'   => 'Material',
        'nomr'           => $nomr,
        'tglrequest'     => date('Y-m-d H:i:s'),
        'requestedby'    => decrypt($CI->session->userdata('karyawan_id')),
        'createdby'      => decrypt($CI->session->userdata('karyawan_id')),
        'remark'         => "Auto generated by system based on MI/MO $mio->nomio",
        'statusmr'       => 'Waiting',
        'statusapproval' => 'Pending',
        'perusahaanid'   => $warehouse->perusahaanid,
        'warehouseid'    => $warehouse->warehouseid,
        'jenismr'        => 'Item For Stock',
        'isautorequest'  => 'Ya',
        'status'         => 1
    ]);

    foreach ($items_tobe_mr as $key => $value) {
        $items_tobe_mr[$key]['mrid'] = $mr_id;
    }

    $CI->Md_mrdetail->addMrdetail($items_tobe_mr);

    //process add approval and notification acknowledge
    $acknowledge = $CI->Md_teamanggota->getTeamanggotaByTeamidAndLevelAndHaksesname($warehouse->teamid, 'Acknowledge', 'Staff');
    $acknowledgeOtherAcc = $CI->Md_teamanggota->getTeamanggotaSCMByTeamidAndLevel($warehouse->teamid, 'Acknowledge');
    $insert_approval = array();
    $insert_notifikasi = array();
    $mail_ack = [];
    $karyawan_ack = array();
    foreach ($acknowledge as $list) {
        $insert_approval[]  = [
            'mrid'          => $mr_id,
            'karyawanid'    => $list->karyawanid,
            'jenisapproval' => 'Acknowledge',
            'status'        => 1,
        ];

        $insert_notifikasi[] = [
            'penggunaid' => $list->penggunaid,
            'info'       => 'Sistem telah membuat MR dan sekarang menunggu persetujuan Anda sebagai acknowledge.',
            'tglpost'    => date('Y-m-d H:i:s'),
            'link'       => base_url() . 'staff/approval_mr/confirm/' . encrypt($mr_id) . '/Acknowledge',
            'readstatus' => 'Belum Baca',
            'status'     => 1,
        ];

        $mail_ack[]     = $list->email;
        $karyawan_ack[] = $list->karyawanid;
    }

    //notification to hak akses warehouse, finance, purchasing, asset
    foreach ($acknowledgeOtherAcc as $list) {
        if (!in_array($list->hakaksesname, ['Finance', 'Purchasing', 'Warehouse', 'Asset'])) {
            continue;
        }

        $insert_notifikasi[] = [
            'penggunaid' => $list->penggunaid,
            'info'       => 'Sistem telah membuat MR dan sekarang menunggu persetujuan Anda sebagai acknowledge.',
            'tglpost'    => date('Y-m-d H:i:s'),
            'link'       => base_url() . strtolower($list->hakaksesname) . '/approval_mr/confirm/' . encrypt($mr_id) . '/Acknowledge',
            'readstatus' => 'Belum Baca',
            'status'     => 1,
        ];
    }

    $isi = 'Dear Bapak/Ibu,<br/><br/>
            Sistem telah membuat MR dengan No ' . $nomr . ' dan sekarang menunggu persetujuan Anda sebagai acknowledge.
            Silahkan login ke <a href="https://stfms.vadhana.co.id">Sistem Informasi STFMS</a> untuk melihat.
            <br/><br/>
            <br><br><br>Terima Kasih.<br><br>';

    $mailbox = [
        'to'          => implode(',', $mail_ack),
        'from'        => 'noreply@vadhana.co.id',
        'subjek'      => 'STFMS - Acknowledge Material/Service Request',
        'tglpost'     => date('Y-m-d H:i:s'),
        'statuskirim' => 'Draft',
        'status'      => 1,
        'isi'         => $isi,
    ];

    //notifikasi mobile
    $notif_tujuan = array();
    $token = $CI->Md_tokenmobile->getTokenFcbBySomeKaryawan($karyawan_ack);
    if ($token) {
        foreach ($token as $list) {
            $notif_tujuan[] = $list->tokenfcb;
        }

        $isi = 'Sistem telah membuat MR dan sekarang menunggu persetujuan Anda sebagai acknowledge.';
        sendmobilenotif($notif_tujuan, "Approval MR", $isi, "mrack");
    }

    $CI->Md_approval->addApproval($insert_approval);
    $CI->Md_notifikasi->addMultipleNotifikasi($insert_notifikasi);
    $CI->Md_mailbox->addMultipleMailbox([$mailbox]);

    addLog('Add Data', 'Menambah data Material Request Otomatis', "Nomor MR $nomr", $log_mr_min_max);

    return 1;
}
