<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://unpkg.com/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
          integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body class="h-screen  items-center justify-center" style="background: #edf2f7;">
<div class="container mx-auto px-4 sm:px-8">
    <h1 class="text-center text-3xl font-bold mb-4 py-4 leading-none">Authenticate as anyone</h1>
    <div class="mx-auto mt-4 rounded">
        <!-- Tabs -->
        <ul id="tabs" class="inline-flex w-full px-1 pt-2 ">
            @foreach($models as $modelName => $model)
                <li class="px-4 py-2 font-semibold text-gray-800 rounded-t opacity-50 {{ $modelName === $currentModelName  ? 'border-b-2 border-blue-400' : '' }}"><a
                        id="default-tab" href="{{ route('authenticate-as-anyone.index', $modelName) }}">
                        {{ $modelName }}
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Tab Contents -->
        <div class="py-4">
            <div class="py-8">
                <form action="{{ route('authenticate-as-anyone.index', $currentModelName) }}">
                    <input class="shadow border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight"
                           id="search"
                           name="search"
                           type="text"
                           value="{{ app('request')->input('search') }}"
                           placeholder='Search user (Press "/" to focus)'>
                </form>
                <div class="mx-4 sm:-mx-8 px-4 sm:px-8 overflow-x-auto">
                    <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                        <table class="min-w-full leading-normal">
                            <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Name
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    First name
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Email
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr class="text-center">
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        {{ $user->aaaName }}
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        {{ $user->aaaFirstName }}
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        {{ $user->aaaLogin }}
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <a href="{{ route('authenticate-as-anyone.auth', ['model' => get_class($user), 'userId' => $user->getAuthIdentifier()]) }}"
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            <i class="fa fa-sign-in-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function getFocus(e) {
        if (e.keyCode === 191 || e.keyCode === 47) {
            e.preventDefault();
            document.getElementById("search").focus();
        }
    }

    document.addEventListener('keypress', getFocus);
</script>
</body>
