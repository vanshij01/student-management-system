@extends('frontend.layouts.app')
@section('title', 'Contact Us')
@section('styles')
@endsection
@section('content')
    <section class="contact-page-sec sec-space">
        <div class="container">
            <a href="{{ route('student.dashboard') }}" class="go-back">
                <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.76172 1.63672L2.39844 9L9.76172 16.3633L8.86328 17.2617L1.05078 9.44922L0.621094 9L1.05078 8.55078L8.86328 0.738281L9.76172 1.63672Z"
                        fill="#1D1D1B" />
                </svg>
                Go Back</a>
            <div class="contact-details-block pt_60">
                <h3 class="admission_form_title">Contact Us</h3>
                <div class="contact-info-wrap">
                    <p>Contact our management if you are facing any issues with the portal.</p>
                    <p><strong>Tel:</strong> <a href="tel:+917927911718">+91 79 27911718</a></p>
                    <p><strong>Mob:</strong> <a href="tel:+919909211718">+91 9909211718</a></p>
                    <p><strong>Email:</strong> <a href="mailto:info@sklpsahmedabad.com">info@sklpsahmedabad.com</a></p>
                </div>
                <div class="sklps-addresses-wrapper">
                    <div class="sklps-addresses-box">
                        <div class="bhavan-name">Atithi Bhavan - (Guest Stay)</div>
                        <div class="sklps-address-txt">
                            <p>B/h, Loyla Hall, Opp. Kamnath Mahadev<br>Saint Xaviers School
                                Road<br>Naranpura-380013<br>Ahmedabad</p>
                        </div>
                    </div>
                    <div class="sklps-addresses-box">
                        <div class="bhavan-name">Kanya Seva Sadan - (Girls Hostel)</div>
                        <div class="sklps-address-txt">
                            <p>3-Sthanakwasi Jain Co.H.Soci,<br>Opp. Rokadiya Hanuman Temple, Naranpura
                                Railway<br>Crossing, Naranpura-380013<br>Ahmedabad</p>
                        </div>
                    </div>
                    <div class="sklps-addresses-box">
                        <div class="bhavan-name">Kumar Seva Sadan - (Boys Hostel)</div>
                        <div class="sklps-address-txt">
                            <p>B/h, Loyla Hall, Opp. Kamnath Mahadev<br>Saint Xaviers School
                                Road<br>Naranpura-380013<br>Ahmedabad</p>
                        </div>
                    </div>
                    <div class="sklps-addresses-box">
                        <div class="bhavan-name">Vidhyarthi Bhavan - (Guest Stay)</div>
                        <div class="sklps-address-txt">
                            <p>B/h, Loyla Hall, Opp. Kamnath Mahadev<br>Saint Xaviers School
                                Road<br>Naranpura-380013<br>Ahmedabad</p>
                        </div>
                    </div>
                    <div class="sklps-addresses-box">
                        <div class="bhavan-name">Seva Sadan</div>
                        <div class="sklps-address-txt">
                            <p>4-Ketan Co.H.Soci, Opp. BD Patel House,<br>Lakhudi Char Rasta,<br>Navranpura –
                                380014<br>Ahmedabad</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
