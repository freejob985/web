<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    /**
     * Get all about page content
     */
    public function index()
    {
        $content = AboutPage::active()->ordered()->get()->groupBy('section');
        
        return response()->json([
            'success' => true,
            'data' => $content
        ]);
    }

    /**
     * Get content by section
     */
    public function getBySection($section)
    {
        $content = AboutPage::section($section)->active()->ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => $content
        ]);
    }

    /**
     * Get hero section
     */
    public function getHero()
    {
        $hero = AboutPage::section(AboutPage::SECTION_HERO)->active()->first();
        
        return response()->json([
            'success' => true,
            'data' => $hero
        ]);
    }

    /**
     * Get story section
     */
    public function getStory()
    {
        $story = AboutPage::section(AboutPage::SECTION_STORY)->active()->first();
        
        return response()->json([
            'success' => true,
            'data' => $story
        ]);
    }

    /**
     * Get values section
     */
    public function getValues()
    {
        $values = AboutPage::section(AboutPage::SECTION_VALUES)->active()->ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => $values
        ]);
    }

    /**
     * Get statistics section
     */
    public function getStatistics()
    {
        $statistics = AboutPage::section(AboutPage::SECTION_STATISTICS)->active()->ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Get team section
     */
    public function getTeam()
    {
        $team = AboutPage::section(AboutPage::SECTION_TEAM)->active()->ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => $team
        ]);
    }

    /**
     * Get mission section
     */
    public function getMission()
    {
        $mission = AboutPage::section(AboutPage::SECTION_MISSION)->active()->first();
        
        return response()->json([
            'success' => true,
            'data' => $mission
        ]);
    }
}
