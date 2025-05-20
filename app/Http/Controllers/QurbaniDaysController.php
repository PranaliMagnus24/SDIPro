<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QurbaniDay;

class QurbaniDaysController extends Controller
{
    public function qurbaniDay()
    {
        $data['getRecord'] = QurbaniDay::find(1);
        return view('qurbanis.qurbani_days', $data);
    }

    public function qurbaniDayStore(Request $request)
    {
        $request -> validate([
            'day_one' => 'required|date',
            'day_two' => 'required|date',
        ]);

         $save = QurbaniDay::find(1);
        $save->day_one = $request->day_one;
        $save->day_two = $request->day_two;

        $save->save();
        return redirect('days')->with('success', 'Days created successfully!');
    }
}
