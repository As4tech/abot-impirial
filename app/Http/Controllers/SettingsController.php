<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function __construct(private SettingsService $settings) {}

    public function index(): View
    {
        $s = $this->settings->all();
        return view('settings', compact('s'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // General
            'general.business_name' => ['nullable','string','max:255'],
            'general.logo' => ['nullable','url'],
            'general.favicon' => ['nullable','url'],
            'general.logo_file' => ['nullable','image','max:2048'],
            'general.favicon_file' => ['nullable','mimetypes:image/x-icon,image/vnd.microsoft.icon,image/png','max:512'],
            'general.remove_logo' => ['nullable','boolean'],
            'general.remove_favicon' => ['nullable','boolean'],
            'general.contact' => ['nullable','string','max:500'],
            // POS
            'pos.tax_rate' => ['nullable','numeric','min:0','max:100'],
            'pos.currency' => ['nullable','string','max:10'],
            'pos.receipt_footer' => ['nullable','string','max:500'],
            // Inventory
            'inventory.low_stock_threshold' => ['nullable','integer','min:0','max:100000'],
            // Rooms
            'rooms.check_in_time' => ['nullable','date_format:H:i'],
            'rooms.check_out_time' => ['nullable','date_format:H:i'],
        ]);

        $flat = [];
        foreach (['general','pos','inventory','rooms'] as $section) {
            foreach (($validated[$section] ?? []) as $k => $v) {
                $flat["$section.$k"] = $v;
            }
        }

        // Handle removals first
        if (filled($request->input('general.remove_logo'))) {
            $flat['general.logo'] = null;
            // ignore any provided URL/file when removing
            unset($flat['general.logo_file']);
        } elseif ($request->hasFile('general.logo_file')) {
            // Handle file upload (override URL key if file provided)
            $path = $request->file('general.logo_file')->store('branding', 'public');
            // Save as root-relative path to avoid APP_URL dependency
            $flat['general.logo'] = '/storage/' . ltrim($path, '/');
        }

        if (filled($request->input('general.remove_favicon'))) {
            $flat['general.favicon'] = null;
            unset($flat['general.favicon_file']);
        } elseif ($request->hasFile('general.favicon_file')) {
            $path = $request->file('general.favicon_file')->store('branding', 'public');
            $flat['general.favicon'] = '/storage/' . ltrim($path, '/');
        }

        $this->settings->setMany($flat);

        return redirect()->route('settings.index')->with('status', 'Settings updated');
    }
}
