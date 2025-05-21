<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        try {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = ActivityLog::select('activity_log.*')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->orderBy('activity_log.id', 'DESC');

            if ($request->moduleId) {
                $data->where('activity_log.log_name', $request->moduleId);
            }

            if ($request->actionId) {
                $data->where('activity_log.event', $request->actionId);
            }

            if ($from != null && $to != null) {
                $data->whereBetween('activity_log.created_at', ["$from" . ' 00:00:00', "$to" . ' 23:59:59']);
            } else {
                if ($from != null) {
                    $data->where('activity_log.created_at', '>=', "$from");
                }
            }

            if ($request->search) {
                $data->where(function ($w) use ($request) {
                    $search = $request->get('search');
                    $w->orWhere('activity_log.log_name', 'LIKE', "%$search%")
                        ->orWhereRaw("DATE_FORMAT(activity_log.created_at, '%d/%m/%Y') LIKE ?", ["%$search%"])
                        ->orWhereRaw("DATE_FORMAT(activity_log.created_at, '%d/%m/%Y %H:%i:%s') LIKE ?", ["%$search%"])
                        ->orWhereRaw("DATE_FORMAT(activity_log.created_at, '%d/%m/%Y %H:%i') LIKE ?", ["%$search%"])
                        ->orWhere('activity_log.event', 'LIKE', "%$search%")
                        ->orWhere('users.name', 'LIKE', "%$search%")
                        ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(activity_log.properties, '$')) LIKE ?", ["%$search%"]);
                });
            }

            $allData = $data->paginate($request->page_length ?? 10);

            $modules = ActivityLog::pluck('log_name')->unique();
            $actions = ActivityLog::pluck('event')->unique();

            return view('backend.activity_log.index', compact('allData', 'request', 'modules', 'actions'));
        } catch (\Throwable $th) {
        }
    }
}
