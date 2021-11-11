import Route from "./Support/Route";

import Home from "../Views/Home.svelte";
import Board from "../Views/Board.svelte";
import Thread from "../Views/Thread.svelte";

import Layout from "../Layout.svelte";

export default Route.define([
    Route.group('', {layout: Layout}, [
        Route.path('/', {view: Home, title: 'Главная'}),
        Route.path('{boardName}', {view: Board}),
        Route.path('{boardName}/{id}', {view: Thread})
    ])
]);