<?php namespace SMS\SSA\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Flash;
use Illuminate\Support\Facades\DB;
use October\Rain\Support\Facades\Input;
use SMS\SSA\Models\Programme;
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
    public $helperType;
    private $tournamentData;

    private $isFullList = false;

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
        return [
            'helperType' => [
                'title' => 'Type of the Helper',
                'description' => 'Select the page type to be applied',
                'default' => '',
                'type'  => 'dropdown',
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->pageLoadVars();
    }

    public function onInit()
    {
        $this->prepareVars();
        $this->pageLoadVars();
    }

    public function prepareVars()
    {
        $this->tournamentData = [];
        $this->helperType = $this->page['helperType'] = $this->property('helperType');
        if($this->helperType == 'tournament-list') {
            $this->isFullList = true;
        }
    }

    public function pageLoadVars()
    {
        switch($this->helperType) {
            case 'tournament':
                $this->page['tournamentData'] = $this->getTournamentList();
                break;
            case 'tournament-list':
                $this->isFullList = true;
                $this->page['programList'] = $this->getProgramsList();
                $this->page['tournamentData'] = $this->getTournamentList();
                break;
        }
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

    public function onTournamentList()
    {
        return $this->tournamentData = $this->page['tournamentData'] = $this->getTournamentList();
    }

    public function getTournamentList()
    {

        $tournamentDetails = [];
        $upcomingDayCount = 5;
        $tournamentDate = Carbon::today()->toDateString();

        if($this->isFullList) {

            $pageNumber = 1;
            $tournaments = Tournament::published();
            if (Input::has('pageNumber') && Input::post('pageNumber') != '') {
                $pageNumber = Input::get('pageNumber');
            }

            if(Input::has('programme') && Input::post('programme') != '' ) {
                $programmeSlug = Input::post('programme');
                $tournaments = $tournaments->whereHas('programme', function($q) use($programmeSlug) {
                    $q->where('slug', $programmeSlug);
                });
            }
            $tournamentDetails['tournaments'] = $tournaments->
            orderByRaw("date >= ? DESC", [$tournamentDate])
            ->orderByRaw("
                CASE
                    WHEN date >= ? THEN date
                    ELSE NULL
                END ASC", [$tournamentDate])
            ->orderByRaw("
                CASE
                    WHEN date < ? THEN date
                    ELSE NULL
                END DESC", [$tournamentDate])
            ->paginate(9, ['*'], 'tournaments', $pageNumber);
        } else {
            if (Input::has('tournamentDate') && Input::post('tournamentDate') != '') {
                $tournamentDate = Carbon::createFromFormat('d-m-Y', Input::post('tournamentDate'));
            }

            $tournamentDetails['upcomingDays'] = Tournament::published()
                ->selectRaw('DISTINCT DATE(date) as unique_date')
                ->where('date', '>=', $tournamentDate)
                ->orderBy('unique_date', 'asc')
                ->pluck('unique_date')
                ->take($upcomingDayCount);

            $pastDayCount = ($upcomingDayCount - count($tournamentDetails['upcomingDays']) == 0) ? 2 : (2 + ($upcomingDayCount - count($tournamentDetails['upcomingDays'])));
            $tournamentDetails['pastDays'] = $pastDays = Tournament::published()
                ->selectRaw('DISTINCT DATE(date) as unique_date')
                ->where('date', '<', $tournamentDate)
                ->orderBy('unique_date', 'desc')
                ->pluck('unique_date')
                ->take($pastDayCount)->reverse();

            $allTournamentDays = $pastDays->merge($tournamentDetails['upcomingDays'])->unique()->toArray();

            $tournaments = Tournament::published()
                ->whereIn(DB::raw('DATE(date)'), $allTournamentDays);
            $tournaments = $tournaments->orderBy('date', 'asc');
            $tournamentDetails['tournaments'] = $tournaments->get();

            $tournamentDetails['calendarDays'] = Tournament::published()
                ->selectRaw('DISTINCT DATE(date) as available_date')
                ->where('date', '>', Carbon::today()->subMonths(6)->startOfDay())
                ->where('date', '<=', Carbon::today()->addYears(1)->endOfDay())
                ->orderBy('available_date', 'asc')
                ->pluck('available_date')
                ->toArray();
        }
        return $tournamentDetails;
    }

    public function getHelperTypeOptions()
    {
        return array(
            '' => 'No type',
            'tournament' => 'Tournament Listing',
        );
    }

    public function onUpdateContainer()
    {
        $this->tournamentData = $this->page['tournamentData'] = $this->getTournamentList();
    }

    public function onPageLoad()
    {
        $this->isFullList = true;
        if(Input::has('programme') && Input::post('programme') != '' ) {
            $this->page['selectedProgramme'] = Input::post('programme');
        }
        return $this->tournamentData = $this->page['tournamentData'] = $this->getTournamentList();
    }

    public function getProgramsList() {
        return Programme::published()
            ->whereHas('tournaments', function($query) {
                $query->published();
            })
            ->pluck('name', 'slug');
    }
}
