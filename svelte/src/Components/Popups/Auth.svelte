<script lang="ts">
import Cookies from "js-cookie";

import API from "../../API";
import { toolbar, consumePrepender } from "../../Storage/Toolbar";
let cookie = Cookies.get('LARABA-TOKEN');
let inputToken: string = $toolbar.token ?? null;

console.log(inputToken);

    function applyToken(): void
    {
        API.applyToken(inputToken).then(r => {
           consumePrepender(r.data.subscriptions, r.data.token);
           Cookies.set('LARABA-TOKEN', r.data.token);
        });
    }


</script>

<div class='row'>
    <div class='col-12 text-center board-title'>
        Войти
    </div>
    <div class='col-12'>
        Вы можете сгенерировать токен: он позволит отслеживать треды и посты на разных девайсах
    </div>
    <div class='col-12'>
        Если вы потеряете токен, восстановить его не получится. Не теряйте его
    </div>
    <div class='col-12 mb-3'>
        Токен это не регистрация. Никто не будет знать, что вы пользуетесь токеном; никто не будет знать, кто является автором поста
    </div>

    <div class='col-3'>

    </div>
    <div class='col-6 px-2 text-center my-auto'>
        <input class='w-100 mb-2' bind:value={inputToken}>
        <span class='button p-2' on:click={applyToken}>Ввести токен</span>
    </div>
</div>