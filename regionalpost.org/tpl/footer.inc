<div class="page">
    <div id="footer-top">
        <div class="fcol footer-fcol">
            <a
                href="{{curlangp}}"
                title="{{site_company}}"
                id="logo-foot"
            >
                <img
                    src="imgs/logo.png"
                    alt="{{site_company}}"
                    title="{{site_company}}"
                />
            </a>
            <ul class="footer-menu">
                {{for fmenu as f}}
                <li>
                    <a
                        href="{{f.url}}"
                        title="{{f.name}}"
                    >
                        {{f.name}}
                    </a>
                </li>
                {{end for fmenu}}
                <li>
                    <a
                        href="{{rpb2b.url}}"
                        title="{{rpb2b.name}}"
                    >
                        {{rpb2b.name}}
                    </a>
                </li>
                <li>
                    <a
                        href="{{st.url}}"
                        title="{{st.name}}"
                    >
                        {{st.name}}
                    </a>
                </li>
                {{if media_kit}}
                <li>
                    <a
                        href="{{media_kit}}"
                        target="_blank"
                        title="{{if curlang(=fr)}}Dossier de presse{{else curlang}}Media Kit{{end if curlang}}"
                    >
                        {{if curlang(=fr)}}Dossier de presse{{else curlang}}Media Kit{{end if curlang}}
                    </a>
                </li>
                {{end if media_kit}}
            </ul>
        </div>
        <div
            id="copytext"
            class="r tr"
        >
            <div
                class="soc-list"
                id="sochead"
            >
                {{for socs as soc}}
                <a
                    href="{{soc.url}}"
                    target="_blank"
                    class="socbutton {{soc.name}}"
                    title="{{soc.title}}"
                >
                    &nbsp;
                </a>
                {{end for socs}}
            </div>
            <a
                href="{{baseaddresslang}}"
                title="{{site_company}}"
            >
                {{site_company}}
            </a>
            <span>&copy; {{years}}. {{if curlang(=fr)}}Tous droits réservés.{{else curlang}}All Rights Reserved.{{end if curlang}}</span>
        </div>
    </div>
</div>
