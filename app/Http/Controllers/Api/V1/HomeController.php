<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateHomePageRequest;
use App\Http\Resources\HomePageResource;
use App\Models\HomePage;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Get the singleton homepage data.
     * If no record exists yet, default values are seeded automatically.
     */
    public function singleton()
    {
        $page = HomePage::singleton();

        return new HomePageResource($page);
    }

    /**
     * Update the singleton homepage data.
     */
    public function updateSingleton(UpdateHomePageRequest $request)
    {
        $page = HomePage::singleton();
        $validated = $request->validated();

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            if ($page->hero_image) {
                Storage::disk('public')->delete($page->hero_image);
            }
            $validated['hero_image'] = $request->file('hero_image')
                ->store('homepage', 'public');
        }

        $page->update($validated);

        return new HomePageResource($page->fresh());
    }
}
