<?php

namespace App\Http\Controllers;

use App\Enums\NotificationRecordType;
use App\Services\FakeDataGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    // query parameters
    const RECORD_ID = 'record-id';
    const RECORD_TYPE = 'record-type';


    public function generateFakeData(FakeDataGenerator $generator)
    {
        $generator->generateFakeDataForUserNotificationsTable(Auth::user());
        return redirect('/dashboard')->with('message', 'Fake data successfuly generated!');
    }

    public function toogleNotificationRead(Request $request): JsonResponse
    {
        $record_id = $request->input(self::RECORD_ID);
        $record_type = $request->input(self::RECORD_TYPE);

        if (!$record_id || !$record_type) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'Invalid parameters',
                'rest ' => $record_id,
                'rest 1' => $record_type
            ], status: 400);
        }

        $related_model_class = NotificationRecordType::from($record_type)->getRecordClass();
        $record = $related_model_class::find($record_id);
        if (!$record) {
            return response()->json([
                'status' => 'FAIL',
                'message' => "Record of type {$record_type} with a such id doesn't exist"
            ], status: 400);
        }

        $record->is_read = !$record->is_read;
        $record->save();

        return response()->json(['status' => 'OK', 'is_read' => $record->is_read]);
    }
}
