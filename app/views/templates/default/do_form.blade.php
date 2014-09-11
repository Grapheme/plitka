@extends(Helper::layout())


@section('style')
@stop


@section('content')

    <form action="{{ URL::route('invoice') }}" method="post">
        <input type="hidden" name="quest_id" value="{{ 4 }}">
        Ваши Имя и Фамилия: <input type="text" name="nickname" value="Иван Петров">
        Сумма к оплате, в валюте платёжной системы:

        <input type="text" name="amount" value="{{ Config::get('site.dengionline.amount') }}">
        Способ оплаты: <select name="mode_type">
            <option value="">Выберите</option>
            <option value="4">WebMoney WMB</option>
            <option value="3">WebMoney WME</option>
        </select>
        <input type="submit" value="Оплатить!">
    </form>

@stop


@section('scripts')
@stop