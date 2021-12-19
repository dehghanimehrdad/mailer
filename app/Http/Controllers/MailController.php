<?php

namespace App\Http\Controllers;

use App\Http\Resources\MailResource;
use App\Jobs\SendMail;
use App\Models\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MailController extends Controller
{
    /**
     * Dispatches jobs to send mails
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function sendMail(Request $request){

        //check if the request is json
        if ($request->json()->count() == 0){
            abort(422);
        }
        // iterate for each mail request
        foreach ($request->json()->all() as $mailRequest){
            // validate request for required fields
            Validator::make($mailRequest, [
                'to' => 'required|max:500',
                'subject' => 'required|max:500',
                'body' => 'required',
            ])->validate();

            // validate attachments if present
            if(isset($mailRequest->attachments)){
                foreach ($mailRequest->attachments as $attachment){
                    Validator::make($attachment, [
                        'name' => 'required|max:500',
                        'base64' => 'required',
                    ])->validate();
                }
            }

            //create mail model and store it
            $mail = new Mail();
            $mail->to = $mailRequest['to'];
            $mail->subject = $mailRequest['subject'];
            $mail->body = $mailRequest['body'];
            $mail->save();

            //set attachments on the mail model
            foreach ($mailRequest['attachments'] as $attachment){
                $mail->addMediaFromBase64($attachment['base64'])->usingFileName($attachment['name'])
                    ->toMediaCollection();
            }

            //dispatch job for later processing
            SendMail::dispatch($mail);
        }
        //send response
        return response()->json(['success' => true]);
    }

    /**
     * Lists sent mail
     *
     * @return JsonResponse
     */
    public function list()
    {
        $sentMails = Mail::whereNotNull('sent_at')->get();
        return response()->json(MailResource::collection($sentMails));
    }
}
