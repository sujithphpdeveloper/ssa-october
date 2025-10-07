<?php namespace Sms\Ssa\Components;

use Cms\Classes\ComponentBase;
use Flash;
use Validator;
use ValidationException;
use SMS\SSA\Models\FormSubmission as SubmissionModel;

/**
 * FormSubmission Component
 *
 * @link https://docs.octobercms.com/4.x/extend/cms-components.html
 */
class FormSubmission extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Form Submission Component',
            'description' => 'No description provided yet...'
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

        $formSubmissions = new SubmissionModel();
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
}
