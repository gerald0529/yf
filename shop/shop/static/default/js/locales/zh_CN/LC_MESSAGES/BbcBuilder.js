var G={"Title":"Zero Framework","Hello World!":"世界，你好！"};


function _(key)
{
    if (G[key])
    {
        return G[key]
    }
    else
    {
        return key;
    }
}
