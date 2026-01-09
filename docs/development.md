# 开发工具

本节介绍在开发过程中可能用到的一些工具和代码模式。

## Node.js 命令行参数解析

### minimist 简介

在开发 Node.js 命令行工具或构建脚本时，经常需要处理命令行参数。`minimist` 是一个轻量级的命令行参数解析库。

### 代码说明

```javascript
var options = minimist(process.argv.slice(2), knownOptions);
```

这行代码的含义如下：

#### 1. `process.argv`

`process.argv` 是 Node.js 提供的一个数组，包含启动 Node.js 进程时传入的所有命令行参数。

例如，执行命令：
```bash
node script.js --name=张三 --age=25 --debug
```

`process.argv` 的值为：
```javascript
[
  '/usr/local/bin/node',     // Node.js 可执行文件的路径
  '/path/to/script.js',      // 脚本文件的路径
  '--name=张三',              // 用户传入的参数
  '--age=25',                // 用户传入的参数
  '--debug'                  // 用户传入的参数
]
```

#### 2. `process.argv.slice(2)`

使用 `slice(2)` 从索引 2 开始截取数组，去掉前两个元素（Node.js 路径和脚本路径），只保留用户传入的实际参数：

```javascript
['--name=张三', '--age=25', '--debug']
```

#### 3. `minimist()`

`minimist()` 函数将命令行参数数组解析成一个便于使用的对象：

```javascript
{
  _: [],                    // 不带标志的参数列表
  name: '张三',             // --name 参数
  age: 25,                  // --age 参数（自动转换为数字）
  debug: true               // --debug 参数（布尔标志）
}
```

#### 4. `knownOptions`

第二个参数 `knownOptions` 是可选的配置对象，用于指定参数的类型和默认值：

```javascript
var knownOptions = {
  string: ['name'],         // 指定 name 参数为字符串类型
  boolean: ['debug'],       // 指定 debug 参数为布尔类型
  default: {                // 设置默认值
    age: 18,
    debug: false
  },
  alias: {                  // 设置参数别名
    n: 'name',
    a: 'age',
    d: 'debug'
  }
};
```

### 完整示例

```javascript
// script.js
var minimist = require('minimist');

var knownOptions = {
  string: ['name', 'env'],
  boolean: ['help', 'version'],
  default: {
    env: 'development'
  },
  alias: {
    h: 'help',
    v: 'version',
    e: 'env'
  }
};

var options = minimist(process.argv.slice(2), knownOptions);

if (options.help) {
  console.log('使用方法: node script.js [选项]');
  console.log('选项:');
  console.log('  --name, -n     设置名称');
  console.log('  --env, -e      设置环境 (默认: development)');
  console.log('  --help, -h     显示帮助信息');
  console.log('  --version, -v  显示版本信息');
  process.exit(0);
}

if (options.version) {
  console.log('版本: 1.0.0');
  process.exit(0);
}

console.log('配置选项:', options);
console.log('名称:', options.name || '未设置');
console.log('环境:', options.env);
```

运行示例：

```bash
# 基本使用
node script.js --name=DeepAdmin --env=production

# 使用别名
node script.js -n DeepAdmin -e production

# 显示帮助
node script.js --help
node script.js -h

# 混合使用
node script.js --name=Test -e dev --debug
```

### 在 DeepAdmin 项目中的应用

虽然 DeepAdmin 是基于 Laravel + Vue.js 的项目，但在开发构建工具或自定义脚本时仍可使用 minimist 处理命令行参数，例如：

- 创建数据迁移脚本；
- 自定义构建流程；
- 开发辅助工具。

### 安装 minimist

如果需要在项目中使用 minimist：

```bash
npm install minimist --save-dev
```

或使用 yarn：

```bash
yarn add minimist --dev
```

### 参考资源

- [minimist GitHub 仓库](https://github.com/minimistjs/minimist)
- [Node.js process.argv 文档](https://nodejs.org/docs/latest/api/process.html#process_process_argv)
