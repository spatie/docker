FROM ubuntu:latest
MAINTAINER Ruben Van Assche <ruben@spatie.be>

# add openssh and clean
RUN apt-get update && apt-get install -y openssh-server sed

RUN mkdir /var/run/sshd

COPY keys /etc/ssh

RUN echo "root:root" | chpasswd

RUN sed -ri 's/^#?PermitRootLogin\s+.*/PermitRootLogin yes/' /etc/ssh/sshd_config
RUN sed -ri 's/^#?PasswordAuthentication yes/PasswordAuthentication no/g' /etc/ssh/sshd_config

RUN mkdir /root/.ssh
RUN touch /root/.ssh/authorized_keys
RUN chmod 600 /root/.ssh/authorized_keys

EXPOSE 22
CMD    ["/usr/sbin/sshd", "-D"]
