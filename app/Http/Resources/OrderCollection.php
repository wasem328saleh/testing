<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => OrderResource::collection($this->collection),
            'total' => $this->total(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'first_page_url' => $this->url(1),
            'last_page_url' => $this->url($this->lastPage()),
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
            'path' => $this->path(),
//            'links' => $this->getPaginatorLinks(),
        ];
    }

    protected function getPaginatorLinks()
    {
        $links = [];
        $currentPage = $this->currentPage();
        $lastPage = $this->lastPage();

        // رابط الصفحة السابقة
        if ($currentPage > 1) {
            $links[] = [
                'url' => $this->previousPageUrl(),
                'label' => '&laquo; Previous',
                'active' => false
            ];
        } else {
            $links[] = [
                'url' => null,
                'label' => '&laquo; Previous',
                'active' => false
            ];
        }

        // روابط الصفحات الرقمية
        $start = max(1, $currentPage - 5);
        $end = min($lastPage, $start + 9);

        for ($i = $start; $i <= $end; $i++) {
            $links[] = [
                'url' => $this->url($i),
                'label' => (string)$i,
                'active' => $i === $currentPage
            ];
        }

        // رابط الصفحة التالية
        if ($currentPage < $lastPage) {
            $links[] = [
                'url' => $this->nextPageUrl(),
                'label' => 'Next &raquo;',
                'active' => false
            ];
        } else {
            $links[] = [
                'url' => null,
                'label' => 'Next &raquo;',
                'active' => false
            ];
        }

        return $links;
    }
}
