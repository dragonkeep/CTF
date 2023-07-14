'''
name="ada lovelace"
print(name.title())  #title()以首字母大写的方式显示每个单词，即将每个单词的首字母都改为大写
print(name.upper()) #全部转为大写
print(name.lower()) #全部转为小写

first_name="ada"
last_name="lovelace"
full_name=first_name+" "+last_name #字符串拼接
print(full_name)
'''
# favorite_language='python '
# print(favorite_language.rstrip())
# name="Eric"
# print("Hello "+name+", would you like to learn some Python today?")
# age =23
# message="Happy "+str(age)+"rd Birthday!" # str()将整型转化为字符串
# print(message)

'''
motorcycles=[]
motorcycles.append('honda')
motorcycles.append('yamaha') #使用append添加元素
motorcycles.append('suzuki')
motorcycles.insert(0,'ducati') #使用insert插入元素，第一个参数是插入的位置
del motorcycles[2]   # 使用del 删除元素
poped_motorcycles=motorcycles.pop() # 使用pop删除列表的元素，返回一个新的列表，原列表不改变
poped_motorcycles=motorcycles.pop(0) # 删除指定位置元素
motorcycles.remove('honda') #根据列表的值进行删除
print(motorcycles)
print(poped_motorcycles)
''''''
cars=['bmw','audi','toyota','subaru']
cars.sort() # 默认按照首字母排序
cars.sort(reverse=True) # 按照字母表相反排序
print(cars)
print(sorted(cars)) # 使用sorted进行临时排序，不改变原列表
cars.reverse() # 逆序输出列表
print(cars)
print(len(cars)) # 获取列表长度
'''
# magicians = ['alice', 'david', 'carolina'] 
#for magician in magicians:
    # print(magician)
# for value in range(1,6):
#     print(value)
# numbers=list(range(1,6)) # 使用list将数字转化为列表
# even_number=list(range(2,11,2)) # 设置步长位2
# print(even_number)

# digits = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0]
# print(min(digits),max(digits),sum(digits)) # 简单数据统计

#列表解析
# squares=[value**2 for value in range(1,11)] # 将for循环中的value值平方加入到列表square中
# print(squares)
# 切片
players = ['charles', 'martina', 'michael', 'florence', 'eli']
players2=players[:]
print(players[1:4])
print(players[:4])
print(players[2:])
print(players[-3:])

