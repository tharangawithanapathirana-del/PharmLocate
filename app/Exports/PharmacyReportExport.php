<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Medicine;
use App\Models\Pharmacy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PharmacyReportExport implements 
    FromCollection, 
    WithHeadings, 
    ShouldAutoSize, 
    WithStyles, 
    WithColumnWidths,
    WithEvents
{
    protected $pharmacy;
    protected $startDate;
    protected $endDate;

    public function __construct($pharmacy, $startDate, $endDate)
    {
        $this->pharmacy = $pharmacy;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $data = [];

        $ordersQuery = Order::with(['customer', 'items.medicine'])
            ->where('pharmacy_id', $this->pharmacy->id);

        if ($this->startDate && $this->endDate) {
            $ordersQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        $orders = $ordersQuery->get();
        $totalRevenue = $orders->sum('total_amount');

        $medicines = Medicine::where('pharmacy_id', $this->pharmacy->id)->get();
        $lowStockItems = $medicines->filter(function($item) {
            return $item->quantity > 0 && $item->quantity <= 10;
        });

        // Summary Section
        $data[] = ['REPORT TYPE', 'VALUE'];
        $data[] = ['Pharmacy Name', $this->pharmacy->name];
        $data[] = ['Report Period', ($this->startDate ?? 'All Time') . ' to ' . ($this->endDate ?? 'Now')];
        $data[] = ['Total Orders', $orders->count()];
        $data[] = ['Total Revenue (LKR)', number_format($totalRevenue, 2)];
        $data[] = ['Total Stock Items', $medicines->count()];
        $data[] = ['Low Stock Items', $lowStockItems->count()];
        $data[] = []; // Empty row separator

        // 🟢 මෙතන තමයි ශීර්ෂකය තියෙන්නේ (Row Index 8)
        $data[] = ['ORDER ID', 'CUSTOMER', 'MEDICINE', 'QTY', 'AMOUNT (LKR)', 'STATUS', 'DATE'];

        foreach ($orders as $order) {
            $data[] = [
                '#PL-' . $order->id,
                $order->customer->name ?? 'N/A',
                $order->items->first()->medicine->name ?? 'N/A',
                $order->items->first()->quantity ?? 0,
                number_format($order->total_amount, 2),
                ucfirst($order->status),
                $order->created_at->format('Y-m-d')
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, 'B' => 25, 'C' => 25, 'D' => 10, 'E' => 15, 'F' => 15, 'G' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Top Header (Row 1)
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A5276']],
        ]);

        // 🟢 දැන් Highlight වෙන්නේ Row 8 (Data Header)
        $sheet->getStyle('A8:G8')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2980B9']],
        ]);

        // Total Revenue (Row 5)
        $sheet->getStyle('A5:B5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '27500A']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EAF3DE']],
        ]);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // 1. Data Rows සඳහා Alternating Colors (Zebra Striping) 
                // අපි පටන් ගන්නේ Row 9 ඉඳන්. 
                for ($row = 9; $row <= $highestRow; $row++) {
                    $color = ($row % 2 == 0) ? 'F8FBFF' : 'FFFFFF'; 
                    
                    $sheet->getStyle('A'.$row.':G'.$row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $color]
                        ]
                    ]);

                    // Conditional Colors for Status
                    $status = $sheet->getCell('F'.$row)->getValue();
                    
                    if ($status === 'Cancelled') {
                        $sheet->getStyle('A'.$row.':G'.$row)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'C0392B'], 'bold' => true],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FDEDEC']]
                        ]);
                    }
                    
                    if ($status === 'Delivered') {
                        $sheet->getStyle('A'.$row.':G'.$row)->applyFromArray([
                            'font' => ['color' => ['rgb' => '27AE60']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EAFAF1']]
                        ]);
                    }
                }

                // 🟢 මුළු Table එක වටේම Thick Border එකක් දැමීම (Row 1 සිට අන්තිම Row දක්වා)
                $sheet->getStyle('A1:G'.$highestRow)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '1A5276'],
                        ],
                        'inside' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D5D8DC'],
                        ],
                    ],
                ]);
            },
        ];
    }
}