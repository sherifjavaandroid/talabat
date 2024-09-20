@extends('view.emails.plain')
@section('body')
    <div class="my-8">
        <table>
            <tr>
                <td style="font-weight: 600;">{{ __('Hello') }} {{ $documentRequest->model->name }},</td>
            </tr>
            @if ($documentRequest->status == 'rejected')
                <tr>
                    <td style="padding-top: 1rem;">{{ __('Your document submission has been rejected.') }}</td>
                </tr>
            @elseif ($documentRequest->status == 'approved')
                <tr>
                    <td style="padding-top: 1rem;">{{ __('Your document submission has been approved.') }}</td>
                </tr>
            @else
                <tr>
                    <td style="padding-top: 1rem;">
                        {{ __('We are requesting a re-submission of document for your account.') }}
                        {{ __('Please login to your account to view the request.') }}
                    </td>
                </tr>
            @endif
            <tr>
                <td style="padding-top: 1rem;">{{ __('Thank you') }},</td>
            </tr>
        </table>
    </div>
@endsection
