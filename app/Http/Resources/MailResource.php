<?php

namespace App\Http\Resources;

use App\Models\Mail;
use Illuminate\Http\Resources\Json\JsonResource;

class MailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Mail $mail */
        $mail = $this;
        return [
            'to' => $mail->to,
            'subject' => $mail->subject,
            'body' => $mail->body,
            'sent_at' => $mail->sent_at,
            'attachments' => AttachmentResource::collection($mail->getMedia()),
        ];
    }
}
