<?php namespace SMS\SSA\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Flash;
use Illuminate\Support\Facades\DB;
use SMS\SSA\Models\Tournament;
use Validator;
use ValidationException;
use SMS\SSA\Models\FormSubmission;


/**
 * SiteHelper Component
 *
 * @link https://docs.octobercms.com/4.x/extend/cms-components.html
 */
class SiteHelper extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Site Helper Component',
            'description' => 'Component will do the common functions in the website'
        ];
    }

    /**
     * @link https://docs.octobercms.com/4.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [];
    }

    public function onSubmit()
    {
        $data = post();

        $formSubmissions = new FormSubmission();
        $formTypes = $formSubmissions->getTypeOptions();

        $rules = [
            'type'  => 'required|in:'.implode(',', array_keys($formTypes)),
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required',
        ];

        $messages = [
            'type.*' => 'Refresh the page and submit the form again.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $formSubmissions->name = $data['name'];
        $formSubmissions->email = $data['email'];
        $formSubmissions->phone = $data['phone'];
        $formSubmissions->type = $data['type'];
        $additionalData = [];
        if ($formSubmissions->type == 'contact') {
            $additionalData = array(
                'message' => $data['message'],
            );
        } elseif ($formSubmissions->type == 'registration') {
            $additionalData = array(
                'school' => $data['school'],
            );
        }
        $formSubmissions->data = $additionalData;
        if ($formSubmissions->save()) {
            Flash::success('Thank you! Your submission has been received successfully.');
        } else {
            Flash::error('Sorry! Your submission has been failed!');
        }
    }

    public function getTournamentList() {

        $tournamentDetails = [];
        $today = Carbon::today()->toDateString();

        $upcomingDayCount = 4;

        $tournamentDetails['upcomingDays'] = Tournament::published()
            ->selectRaw('DISTINCT DATE(date) as unique_date')
            ->where('date', '>=', $today)
            ->orderBy('unique_date', 'asc')
            ->pluck('unique_date')
            ->take($upcomingDayCount);

        $pastDayCount = ($upcomingDayCount - count($tournamentDetails['upcomingDays']) == 0)? 2: (2+($upcomingDayCount - count($tournamentDetails['upcomingDays'])));
        $tournamentDetails['pastDays'] = $pastDays = Tournament::published()
            ->selectRaw('DISTINCT DATE(date) as unique_date')
            ->where('date', '<', $today)
            ->orderBy('unique_date', 'asc')
            ->pluck('unique_date')
            ->take($pastDayCount);

        $allTournamentDays = $pastDays->merge($tournamentDetails['upcomingDays'])->unique()->toArray();
        $tournaments = Tournament::published()
            ->whereIn(DB::raw('DATE(date)'), $allTournamentDays);
        $tournaments = $tournaments->orderBy('date', 'asc');
        $tournamentDetails['tournaments'] = $tournaments->get();

        $tournamentDetails['calendarDays'] = Tournament::published()
            ->selectRaw('DISTINCT DATE(date) as available_date')
            ->where('date', '>=', Carbon::today()->subMonths(6)->startOfDay())
            ->where('date', '<=', Carbon::today()->addYears(1)->endOfDay())
            ->orderBy('available_date', 'asc')
            ->pluck('available_date')
            ->toArray();

        return $tournamentDetails;
    }
}
