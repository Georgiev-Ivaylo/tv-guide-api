<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetChannelsRequest;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetChannelsRequest $request)
    {
        $channels = Channel::query();

        if ($request->input('query') || $request->input('date')) {
            $channels->join('programs as p', 'p.channel_id', 'channels.id')
                ->select('channels.*')
                ->groupBy('channels.id');

            if ($request->input('query')) {
                $channels->where('p.title', 'LIKE', '%' . $request->input('query') . '%');
            }

            if ($request->input('date')) {
                $date = Carbon::parse($request->input('date'));

                $channels->where('p.start_datetime', '>=', $date->startOfDay()->toDateTimeString());
                $channels->where('p.start_datetime', '<=', $date->endOfDay()->toDateTimeString());

                $channels->with(['programs' => function ($q) use ($date) {
                    $q->where('start_datetime', '>=', $date->startOfDay()->toDateTimeString());
                    $q->where('start_datetime', '<=', $date->endOfDay()->toDateTimeString());
                }]);
            }
        }

        $pageSize = $request->input('page_size', -1);
        if ($pageSize === -1) {
            return ChannelResource::collection($channels->get());
        }
        if ($request->input('get_pages')) {
            return ChannelResource::collection($channels->paginate($pageSize));
        }
        return ChannelResource::collection($channels->simplePaginate($pageSize));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Channel $channel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Channel $channel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel)
    {
        //
    }
}
