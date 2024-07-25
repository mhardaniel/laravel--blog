<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagsCollection;
use App\Models\Tag;

class TagController extends Controller
{
    public function list()
    {
        return new TagsCollection(Tag::all());
    }
}
