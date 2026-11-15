<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $title = null,
        public bool $fullWidth = false,
        public ?bool $withSidebar = null,
    ) {
        if ($this->withSidebar === null) {
            $this->withSidebar = auth()->check() && request()->routeIs([
                'admin.*',
                'author.*',
                'student.dashboard',
                'student.my-courses',
            ]);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
