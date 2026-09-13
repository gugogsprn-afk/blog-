<div class="b2b page">
    <h1 class="b2b__title">{{item.ainfo}}</h1>
    <div>{{item.content}}</div>

    <h2 class="b2b__title-2">{{if curlang(=fr)}}Liste des services :{{else curlang}}{{if curlang(=ru)}}Список услуг:{{else curlang}}List of services:{{end if curlang}}{{end if curlang}}</h2>
    <ol class="b2b__items">
        {{for flatitems as item}}
            <li class="b2b__items-item">
                {{if item.filename}}
                <img src="{{item.filename}}" alt="{{item.name}}">
                {{end if item.filename}}
                <div>
                    <h3 class="b2b__items-name">{{item.name}}</h3>
                    <div class="b2b__items-content">{{item.content}}</div>
                </div>
            </li>
        {{end for flatitems}}
    </ol>
</div>
