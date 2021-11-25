<script lang="ts">
    import API from "../../API";
    function createToken() {
        API.generateToken().then(r => console.log(r));
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

    <div class='col-6 px-2 text-center my-auto'>
        <input class='w-100 mb-2'>
        <span class='button p-2'>Ввести токен</span>
    </div>
    <div class='col-6 px-2 text-center my-auto'>
        <span class='button p-2' on:click={createToken}>
            Сгенерировать токен
        </span>
    </div>
</div>