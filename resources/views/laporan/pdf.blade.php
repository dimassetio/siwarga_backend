<!DOCTYPE html>
<html>

<head>
  <title>Laporan Jumlah Penduduk</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      font-size: 12px;
    }

    th,
    td {
      border: 1px solid black;
      padding: 8px;
      text-align: center;
    }

    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    td {
      vertical-align: middle;
    }

    .center {
      text-align: center;
    }

    .highlight {
      background-color: #eaf7ff;
      /* Optional row or cell highlight */
    }
  </style>

</head>

<body>
  <p style="text-align: center;"><strong>Laporan Data Warga Kelurahan Batuceper</strong></p>
  @php
    \Carbon\Carbon::setLocale('id'); // Set the locale to Indonesian
    $currentDate = \Carbon\Carbon::now()->translatedFormat('d F Y');
  @endphp

  <p>Tanggal Cetak Laporan: {{ $currentDate }}</p>

  <table>
    <tr>
      <th style="width: 6%;" rowspan="2">NO</th>
      <th style="width: 22%;" rowspan="2">Nama</th>
      <th style="width: 6%;" rowspan="2">RT</th>
      <th style="width: 6%;" rowspan="2">RW</th>
      <th style="width: 40%;" colspan="4">Jumlah Warga</th>
      <th style="width: 20%;" rowspan="2">Keterangan</th>
    </tr>
    <tr>
      <th style="width: 10%;">P</th>
      <th style="width: 10%;">L</th>
      <th style="width: 10%;">Total</th>
      <th style="width: 10%;">KK</th>
    </tr>
    @foreach ($data as $index => $rt)
      <tr>
        <td>{{ $index + 1 }}</td>
        @if (isset($rt->approvedWarga))
          <td>{{ $rt->approvedWarga->createdBy->name }}</td>
          <td>{{ $rt->number }}</td>
          <td>{{ $rt->rw->number }}</td>
          <td>{{ $rt->approvedWarga->jumlah_laki }}</td>
          <td>{{ $rt->approvedWarga->jumlah_perempuan }}</td>
          <td>{{ $rt->approvedWarga->getTotalWarga() }}</td>
          <td>{{ $rt->approvedWarga->jumlah_kk }}</td>
        @else
          <td>-</td>
          <td>{{ $rt->number }}</td>
          <td>{{ $rt->rw->number }}</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
        @endif
        <td></td>
      </tr>
    @endforeach
    <tr>
      <td colspan="4"><strong>Total</strong></td>
      <td>{{ $summary['total_laki'] }}</td>
      <td>{{ $summary['total_perempuan'] }}</td>
      <td>{{ $summary['total_warga'] }}</td>
      <td>{{ $summary['total_kk'] }}</td>
      <td></td>
    </tr>
  </table>

  <p><br></p>
  <p><br></p>
</body>

</html>
