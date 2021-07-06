@if(session()->has('aaa.origin-user'))
    <div class="aaa-banner">
        <p class="aaa-banner__content">
            Currently logged in as <b>{{ session('aaa.user')->aaaFirstName }} {{ session('aaa.user')->aaaName }}</b>
            <span class="aaa-banner__from">{{ get_class(session('aaa.user')) }}</span>
        </p>
        <a class="aaa-banner__btn"
            href="{{ route('authenticate-as-anyone.auth', ['model' => get_class(session('aaa.origin-user')), 'userId' => session('aaa.origin-user')->getAuthIdentifier()]) }}">
            Log back
        </a>
    </div>
    <style>
        .aaa-banner {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            height: 56px;
            z-index: 9000;
            padding: 0 15px;

            background-color: #f0f0f0;
        }

        body {
            padding-top: 56px;
        }

        .aaa-banner__content {
            color: #333;
            font-size: 14px;
            margin: 0;
        }

        .aaa-banner__content b {
            font-weight: bold;
        }

        .aaa-banner__from {
            display: inline-block;
            padding: 0 4px;

            font-size: 12px;

            border-radius: 3px;
            background-color: #d7d7d7;
        }

        .aaa-banner__btn {
            display: inline-block;
            padding: 3px 12px;
            min-width: 80px;
            text-align: center;
            margin-left: 15px;

            font-size: 14px;
            font-weight: bold;
            color: #f0f0f0;

            border-radius: 5px;
            background-color: #818cf8;
            transition: background-color 90ms ease;
        }

        .aaa-banner__btn:hover {
            color:#f0f0f0;
            text-decoration: none;
            background-color: #4c57c1;
        }

        @media (max-width: 640px) {
            .aaa-banner__content {
                font-size: 12px;
            }

            .aaa-banner__from {
                font-size: 10px;
            }

            .aaa-banner__btn {
                font-size: 12px;
            }
        }
    </style>
@endif
