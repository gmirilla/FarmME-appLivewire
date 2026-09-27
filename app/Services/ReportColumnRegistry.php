<?php

namespace App\Services;

use App\Models\internalinspection;

class ReportColumnRegistry
{
    public const ENTRANCE = 'Entrance';
    public const STANDARD = 'NIL';

    /**
     * Determine which fixed column set a report belongs to.
     */
    public function reportTypeFor(string $reportName): string
    {
        return str_contains($reportName, 'Entrance') ? self::ENTRANCE : self::STANDARD;
    }

    /**
     * Ordered, whitelisted column definitions for a report type.
     * Each entry is ['label' => string, 'value' => callable(internalinspection $inspection, string $season): string].
     *
     * @return array<string, array{label: string, value: callable}>
     */
    public function columnsFor(string $reportType): array
    {
        // Report-specific definitions win over the generic farm fields on key collisions.
        return $this->baseColumnsFor($reportType) + $this->farmColumns();
    }

    /**
     * The default column set shown when a user has no selection or saved preference.
     * The extra farm-model fields are selectable but not part of the default.
     */
    public function defaultKeysFor(string $reportType): array
    {
        return array_keys($this->baseColumnsFor($reportType));
    }

    /**
     * Reduce a candidate list of keys down to those that are valid for the report type,
     * preserving registry order. Unknown/stale keys are silently dropped.
     */
    public function sanitizeKeys(string $reportType, array $keys): array
    {
        $allowed = array_keys($this->columnsFor($reportType));

        return array_values(array_intersect($allowed, $keys));
    }

    private function baseColumnsFor(string $reportType): array
    {
        return $reportType === self::ENTRANCE
            ? $this->entranceColumns()
            : $this->standardColumns();
    }

    /**
     * Every field on the farm model, available to all report types. signaturepath is
     * omitted as it is a file path with no meaning in a tabular report.
     */
    private function farmColumns(): array
    {
        $fields = [
            'fname' => 'First Name',
            'surname' => 'Surname',
            'community' => 'Community',
            'village' => 'Village',
            'state' => 'State',
            'region' => 'Region',
            'address' => 'Address',
            'farmstate' => 'Farm State',
            'lastinspection' => 'Last Inspection',
            'nextinspection' => 'Next Inspection',
            'farmarea' => 'Farm Area',
            'measurement' => 'Measurement',
            'nooffarmunits' => 'No of Farm Units',
            'yearofcertification' => 'Year of Certification',
            'householdsize' => 'Household Size',
            'noofpermworkers' => 'No of Permanent Workers',
            'nooftempworkers' => 'No of Temporary Workers',
            'crop' => 'Crop',
            'cropvariety' => 'Crop Variety',
        ];

        $columns = [];
        foreach ($fields as $field => $label) {
            $columns['farm_' . $field] = [
                'label' => $label,
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->{$field},
            ];
        }

        // Keys shared with the report-specific sets, so a report's own definition wins.
        $columns['gender'] = [
            'label' => 'Gender',
            'value' => fn (internalinspection $i, string $season) => $i->getfarm()->gender,
        ];
        $columns['yob'] = [
            'label' => 'Year of Birth',
            'value' => fn (internalinspection $i, string $season) => $i->getfarm()->yob,
        ];
        $columns['national_id'] = [
            'label' => 'ID NO',
            'value' => fn (internalinspection $i, string $season) => $i->getfarm()->nationalidnumber,
        ];
        $columns['house_lat'] = [
            'label' => 'House Lat.',
            'value' => fn (internalinspection $i, string $season) => $i->getfarm()->latitude,
        ];
        $columns['house_long'] = [
            'label' => 'House Long.',
            'value' => fn (internalinspection $i, string $season) => $i->getfarm()->longitude,
        ];
        $columns['farm_inspector'] = [
            'label' => 'Inspector',
            'value' => fn (internalinspection $i, string $season) => $i->getfarm()->getinspectorName(),
        ];

        return $columns;
    }

    private function entranceColumns(): array
    {
        return [
            'farmer_name' => [
                'label' => 'Farmer Name',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->farmname,
            ],
            'phone_number' => [
                'label' => 'Phone Number',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->phonenumber ?? 'N/A',
            ],
            'farm_code' => [
                'label' => 'Farm Code',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->farmcode,
            ],
            'gender' => [
                'label' => 'Gender',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->gender,
            ],
            'yob' => [
                'label' => 'Year of Birth',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->yob,
            ],
            'national_id' => [
                'label' => 'ID NO',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->nationalidnumber,
            ],
            'plot_name' => [
                'label' => 'Plot name',
                'value' => fn (internalinspection $i, string $season) => $i->getplotdetails()->plotname ?? 'N/A',
            ],
            'plot_size' => [
                'label' => 'Plot Size (ha)',
                'value' => fn (internalinspection $i, string $season) => $i->getplotdetails()->fuarea ?? 'N/A',
            ],
            'plot_lat' => [
                'label' => 'Plot Lat.',
                'value' => fn (internalinspection $i, string $season) => $i->getplotdetails()->fulatitude ?? 'N/A',
            ],
            'plot_long' => [
                'label' => 'Plot Long.',
                'value' => fn (internalinspection $i, string $season) => $i->getplotdetails()->fulongitude ?? 'N/A',
            ],
            'plot_count' => [
                'label' => 'No of Plots',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->getreportfarmcount($season),
            ],
            'total_farm_size' => [
                'label' => 'Total Farm Size (ha)',
                'value' => fn (internalinspection $i, string $season) => number_format($i->getfarm()->getreportfarmarea($season), 2),
            ],
            'estimated_yield' => [
                'label' => 'Estimated yield (kg)',
                'value' => fn (internalinspection $i, string $season) => !empty($i->farmentrance) ? number_format($i->farmentrance->getestimatedyield(), 2) : '',
            ],
            'non_ginger_hectare' => [
                'label' => 'Non Ginger Hectare',
                'value' => fn (internalinspection $i, string $season) => !empty($i->farmentrance) ? number_format($i->getothercropsize(), 4) : '',
            ],
            'prev_year_del' => [
                'label' => 'Previous Year Del.',
                'value' => fn (internalinspection $i, string $season) => (!empty($i->farmentrance) && !empty($i->farmentrance->reportvolcropdel()[0]))
                    ? number_format($i->farmentrance->reportvolcropdel()[0]->value, 2) : '',
            ],
            'prev_2years_del' => [
                'label' => 'Previous 2 Years Del.',
                'value' => fn (internalinspection $i, string $season) => (!empty($i->farmentrance) && !empty($i->farmentrance->reportvolcropdel()[1]))
                    ? number_format($i->farmentrance->reportvolcropdel()[1]->value, 2) : '',
            ],
            'prev_3years_del' => [
                'label' => 'Previous 3 Years Del.',
                'value' => fn (internalinspection $i, string $season) => (!empty($i->farmentrance) && !empty($i->farmentrance->reportvolcropdel()[2]))
                    ? number_format($i->farmentrance->reportvolcropdel()[2]->value, 2) : '',
            ],
        ];
    }

    private function standardColumns(): array
    {
        return [
            'farmer_name' => [
                'label' => 'Farmer Name',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->farmname,
            ],
            'farm_code' => [
                'label' => 'Farm Code',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->farmcode,
            ],
            'phone_number' => [
                'label' => 'Phone Number',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->phonenumber,
            ],
            'house_lat' => [
                'label' => 'House Lat.',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->latitude,
            ],
            'house_long' => [
                'label' => 'House Long.',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->longitude,
            ],
            'plot_count' => [
                'label' => 'No of Plots',
                'value' => fn (internalinspection $i, string $season) => $i->getfarm()->getreportfarmcount($season),
            ],
            'total_farm_size' => [
                'label' => 'Total Farm Size (ha)',
                'value' => fn (internalinspection $i, string $season) => number_format($i->getfarm()->getreportfarmarea($season), 2),
            ],
            'conditions' => [
                'label' => 'Approval Committee Conditions',
                'value' => function (internalinspection $i, string $season) {
                    $html = '<b>IMS Comments: </b>' . e($i->comments ?? '');
                    if (!empty($i->conditions)) {
                        $html .= '<br/><b>Committee: </b>' . e($i->conditions);
                    }

                    return new \Illuminate\Support\HtmlString($html);
                },
            ],
        ];
    }
}
