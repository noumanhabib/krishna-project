<?php



namespace App\Exports;



use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;



class RenewhubStatus implements FromCollection, WithHeadings

{

    protected $data;



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function __construct($data)

    {

        $this->data = $data;
    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function collection()

    {

        return collect($this->data);
    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function headings(): array

    {

        return [
            'Date',
            // 'New_pin',
            'Barcode',
            'Brand_Name',
            // 'Brand_Status',
            'Model_Name',
            // 'Model_Status',
            'Part_Name',
            'SKU_NO',
            'Status',
        ];
    }
}
