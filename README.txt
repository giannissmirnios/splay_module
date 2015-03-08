splay_module
============
A joomla module that showcases articles that are currently trending. 
Articles are considered trending when they surpass a certain amount of hits in a given time.
The administrator can set the amount of hits,  the given time,  the number of menu items and horizontal or vertical menu layout.

The trending articles are determined by a MySql scheduled event and are managed under a Splay tree in order to optimize the database operations.

This module was created by I.Smirnios for his diploma in Computers Engineering & Informatics department of University of Patras in cooperation with Graphics, Multimedia & GIS Laboratory.

Video demonstration: http://vimeo.com/112852823

This module has been tested for Joomla! version 2.5 and Joomla! version 3.3 .

In order for this joomla module to work correctly the following admin rights should be enabled for the sql user of the joomla implementation:
EVENT
SUPER

The system variable thread_stack should also be set to at least 2mb.

Feel free to fork this module's source code and develop it's functionalities further.
Also, feel free to contact me for anything related to this module at smyrnios [at] ceid.upatras.gr

DISCLAIMER
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, 
INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT 
NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR 
PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY
OF SUCH DAMAGE.
